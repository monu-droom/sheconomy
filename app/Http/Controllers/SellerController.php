<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Seller;
use App\User;
use App\SellerAccountTypeMapping;
use App\Shop;
use App\AccountType;
use App\Product;
use App\Order;
use App\SellerKyc;
use App\OrderDetail;
use App\Mail\RegisterMailManager;
use Illuminate\Support\Facades\Hash;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $approved = null;
        $sellers = Seller::orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $user_ids = User::where('user_type', 'seller')->where(function($user) use ($sort_search){
                $user->where('name', 'like', '%'.$sort_search.'%')->orWhere('email', 'like', '%'.$sort_search.'%');
            })->pluck('id')->toArray();
            $sellers = $sellers->where(function($seller) use ($user_ids){
                $seller->whereIn('user_id', $user_ids);
            });
        }
        if ($request->approved_status != null) {
            $approved = $request->approved_status;
            $sellers = $sellers->where('verification_status', $approved);
        }
        $sellers = $sellers->paginate(15);
        return view('sellers.index', compact('sellers', 'sort_search', 'approved'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sellers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(User::where('email', $request->email)->first() != null){
            flash(__('Email already exists!'))->error();
            return back();
        }
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->user_type = "seller";
        $user->password = Hash::make($request->password);
        if($user->save()){
            $is_user = Seller::where('user_id', $user->id)->first();
            if(!empty($is_user)){
                flash(__('Seller Already Existed!'))->error();
                return back();
            }
            $seller = new Seller;
            $seller->user_id = $user->id;
            if($seller->save()){
                $shop = new Shop;
                $shop->user_id = $user->id;
                $shop->slug = 'demo-shop-'.$user->id;
                $data = [
                    'name' => $user->name,
                    'view' => 'emails.register',
                    'subject' => 'Thank you for registering',
                    'from' => env('MAIL_USERNAME')
                ];
                if($shop->save()){
                    Mail::to($user->email)->send(new RegisterMailManager($data));
                }
                flash(__('Seller has been inserted successfully'))->success();
                return redirect()->route('sellers.index');
            }
        }

        flash(__('Something went wrong'))->error();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $seller = Seller::findOrFail(decrypt($id));
        return view('sellers.edit', compact('seller'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $seller = Seller::findOrFail($id);
        $user = $seller->user;
        $user->name = $request->name;
        $user->email = $request->email;
        if(strlen($request->password) > 0){
            $user->password = Hash::make($request->password);
        }
        if($user->save()){
            if($seller->save()){
                flash(__('Seller has been updated successfully'))->success();
                return redirect()->route('sellers.index');
            }
        }

        flash(__('Something went wrong'))->error();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $seller = Seller::findOrFail($id);
        Shop::where('user_id', $seller->user->id)->delete();
        Product::where('user_id', $seller->user->id)->delete();
        Order::where('user_id', $seller->user->id)->delete();
        OrderDetail::where('seller_id', $seller->user->id)->delete();
        User::destroy($seller->user->id);
        if(Seller::destroy($id)){
            flash(__('Seller has been deleted successfully'))->success();
            return redirect()->route('sellers.index');
        }
        else {
            flash(__('Something went wrong'))->error();
            return back();
        }
    }

    public function show_verification_request($id)
    {
        $seller = Seller::findOrFail($id);        
        $shop = Shop::where('user_id', $seller->user_id)->first();
        $account = SellerAccountTypeMapping::where('seller_id', $seller->id)->first();
        $account_type = AccountType::where('id', $account->account_type_id)->first();
        $seller_type = '';
        $seller_kind_1 = array('individuals', 'sole proprietors', 'freelancers', 'consultants');
        $seller_kind_2 = array('registered business/company', 'authorized reseller', 'partnership', 'trading company', 'ngo');
        if(in_array($account_type->account_type, $seller_kind_1)){
            $seller_type = 'individual';
        }
        if(in_array($account_type->account_type, $seller_kind_2)){
            $seller_type = 'pro';
        }
        $kyc_status = SellerKyc::where('seller_id', $id)->first();
        return view('sellers.verification', compact('seller','account_type', 'shop', 'seller_type', 'kyc_status'));
    }

    public function approve_seller($id)
    {
        $seller = Seller::findOrFail($id);
        $seller->verification_status = 1;
        if($seller->save()){
            flash(__('Seller has been approved successfully'))->success();
            return redirect()->route('sellers.index');
        }
        flash(__('Something went wrong'))->error();
        return back();
    }

    public function reject_seller($id)
    {
        $seller = Seller::findOrFail($id);
        $seller->verification_status = 0;
        $seller->verification_info = null;
        if($seller->save()){
            flash(__('Seller verification request has been rejected successfully'))->success();
            return redirect()->route('sellers.index');
        }
        flash(__('Something went wrong'))->error();
        return back();
    }


    public function payment_modal(Request $request)
    {
        $seller = Seller::findOrFail($request->id);
        return view('sellers.payment_modal', compact('seller'));
    }

    public function profile_modal(Request $request)
    {
        $seller = Seller::findOrFail($request->id);
        return view('sellers.profile_modal', compact('seller'));
    }

    public function updateApproved(Request $request)
    {
        $seller = Seller::findOrFail($request->id);
        $seller->verification_status = $request->status;
        if($seller->save()){
            return 1;
        }
        return 0;
    }

    public function login($id)
    {
        $seller = Seller::findOrFail(decrypt($id));

        $user  = $seller->user;

        auth()->login($user, true);

        return redirect()->route('dashboard');
    }
    
   //verifivation---Monu Dahiya---Nov-12-2020
    public function kycByAdmin(Request $request){
        $inputs = $request->all();
        $seller_id = $request->route('id');
        $kyc_details = SellerKyc::where('seller_id', $seller_id)->first();
        if(!empty($kyc_details)){
            $kyc_details = SellerKyc::find($kyc_details->id);            
        }
        if($kyc_details){
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'age_proof_accpeted'){
                $kyc_details->age_proof_verified = 1;
                $kyc_details->seller_id = $request->route('id');
                if($kyc_details->save()){
                    flash(__('Age Proof Verified successfully!'))->success();
                }
            }
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'age_proof_rejected'){
                $kyc_details->age_proof_verified = 0;
                $kyc_details->seller_id = $seller_id;
                $seller = Seller::find($seller_id);
                if(!empty($seller)){
                    $seller->kyc_status = 'rejected';
                    $seller->save();
                }
                if($kyc_details->save()){
                    flash(__('Age Proof Rejected successfully!'))->success();
                }
            }
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'address_proof_accpeted'){
                $kyc_details->address_proof_verified = 1;
                $kyc_details->seller_id = $request->route('id');
                if($kyc_details->save()){
                    flash(__('Address Proof Verified successfully!'))->success();
                }
            }
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'address_proof_rejected'){
                $kyc_details->address_proof_verified = 0;
                $kyc_details->seller_id = $seller_id;
                $seller = Seller::find($seller_id);
                if(!empty($seller)){
                    $seller->kyc_status = 'rejected';
                    $seller->save();
                }
                if($kyc_details->save()){
                    flash(__('Address Proof Rejected successfully!'))->success();
                }
            }
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'tax_proof_accpeted'){
                $kyc_details->tax_verified = 1;
                $kyc_details->seller_id = $request->route('id');
                if($kyc_details->save()){
                    flash(__('Tax Proof Verified successfully!'))->success();
                }
            }
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'tax_proof_rejected'){
                $kyc_details->tax_verified = 0;
                $kyc_details->seller_id = $seller_id;
                $seller = Seller::find($seller_id);
                if(!empty($seller)){
                    $seller->kyc_status = 'rejected';
                    $seller->save();
                }
                if($kyc_details->save()){
                    flash(__('Tax Proof Rejected successfully!'))->success();
                }
            }
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'business_proof_accpeted'){
                $kyc_details->business_verified = 1;
                $kyc_details->seller_id = $request->route('id');
                if($kyc_details->save()){
                    flash(__('Business Proof Verified successfully!'))->success();
                }
            }
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'business_proof_rejected'){
                $kyc_details->business_verified = 0;
                $kyc_details->seller_id = $seller_id;
                $seller = Seller::find($seller_id);
                if(!empty($seller)){
                    $seller->kyc_status = 'rejected';
                    $seller->save();
                }
                if($kyc_details->save()){
                    flash(__('Business Proof Rejected successfully!'))->success();
                }
            }
        }else{
            $kyc = new Sellerkyc;            
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'age_proof_accpeted'){
                $kyc->age_proof_verified = 1;
                $kyc->seller_id = $request->route('id');
                if($kyc->save()){
                    flash(__('Age Proof Verified successfully!'))->success();
                }
            }
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'age_proof_rejected'){
                $kyc->age_proof_verified = 0;
                $kyc->seller_id = $seller_id;
                $seller = Seller::find($seller_id);
                if(!empty($seller)){
                    $seller->kyc_status = 'rejected';
                    $seller->save();
                }
                if($kyc->save()){
                    flash(__('Age Proof Rejected successfully!'))->success();
                }
            }            
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'address_proof_accpeted'){
                $kyc->address_proof_verified = 1;
                $kyc->seller_id = $request->route('id');
                if($kyc->save()){
                    flash(__('Age Proof Verified successfully!'))->success();
                }
            }
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'address_proof_rejected'){
                $kyc->address_proof_verified = 0;
                $kyc->seller_id = $seller_id;
                $seller = Seller::find($seller_id);
                if(!empty($seller)){
                    $seller->kyc_status = 'rejected';
                    $seller->save();
                }
                if($kyc->save()){
                    flash(__('Address Proof Rejected successfully!'))->success();
                }
            }            
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'tax_proof_accpeted'){
                $kyc->tax_verified = 1;
                $kyc->seller_id = $request->route('id');
                if($kyc->save()){
                    flash(__('Tax Proof Verified successfully!'))->success();
                }
            }
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'tax_proof_rejected'){
                $kyc->tax_verified = 0;
                $kyc->seller_id = $seller_id;
                $seller = Seller::find($seller_id);
                if(!empty($seller)){
                    $seller->kyc_status = 'rejected';
                    $seller->save();
                }
                if($kyc->save()){
                    flash(__('Tax Proof Rejected successfully!'))->success();
                }
            }             
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'business_proof_accpeted'){
                $kyc->business_verified = 1;
                $kyc->seller_id = $request->route('id');
                if($kyc->save()){
                    flash(__('Business Proof Verified successfully!'))->success();
                }
            }
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'business_proof_rejected'){
                $kyc->business_verified = 0;
                $kyc->seller_id = $seller_id;
                $seller = Seller::find($seller_id);
                if(!empty($seller)){
                    $seller->kyc_status = 'rejected';
                    $seller->save();
                }
                if($kyc->save()){
                    flash(__('Business Proof Rejected successfully!'))->success();
                }
            }           
        }
        return redirect()->route('sellers.show_verification_request', $seller_id);
    }  
    //verifivation---Monu Dahiya---Nov-12-2020
    public function indianKycByAdmin(Request $request){
        $inputs = $request->all();
        $seller_id = $request->route('id');
        $kyc_details = SellerKyc::where('seller_id', $seller_id)->first();
        if(!empty($kyc_details)){
            $kyc_details = SellerKyc::find($kyc_details->id);            
        }
        if($kyc_details){
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'aadhar_proof_accpeted'){
                $kyc_details->aadhar_verified = 1;
                $kyc_details->aadhar_pre_verified = 1;
                $kyc_details->seller_id = $request->route('id');
                if($kyc_details->save()){
                    flash(__('Aadhar Proof Verified successfully!'))->success();
                }
            }
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'aadhar_proof_rejected'){
                $kyc_details->aadhar_verified = 0;
                $kyc_details->aadhar_pre_verified = 0;
                $kyc_details->seller_id = $seller_id;
                $seller = Seller::find($seller_id);
                if(!empty($seller)){
                    $seller->kyc_status = 'rejected';
                    $seller->save();
                }
                if($kyc_details->save()){
                    flash(__('Aadhar Proof Rejected successfully!'))->success();
                }
            }
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'pan_proof_accpeted'){
                $kyc_details->pan_verified = 1;
                $kyc_details->pan_pre_verified = 1;
                $kyc_details->seller_id = $request->route('id');
                if($kyc_details->save()){
                    flash(__('PAN Proof Verified successfully!'))->success();
                }
            }
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'pan_proof_rejected'){
                $kyc_details->pan_verified = 0;
                $kyc_details->pan_pre_verified = 0;
                $kyc_details->seller_id = $seller_id;
                $seller = Seller::find($seller_id);
                if(!empty($seller)){
                    $seller->kyc_status = 'rejected';
                    $seller->save();
                }
                if($kyc_details->save()){
                    flash(__('PAN Proof Rejected successfully!'))->success();
                }
            }
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'gst_proof_accpeted'){
                $kyc_details->gst_verified = 1;
                $kyc_details->seller_id = $request->route('id');
                if($kyc_details->save()){
                    flash(__('GST Proof Verified successfully!'))->success();
                }
            }
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'gst_proof_rejected'){
                $kyc_details->gst_verified = 0;
                $kyc_details->seller_id = $seller_id;
                $seller = Seller::find($seller_id);
                if(!empty($seller)){
                    $seller->kyc_status = 'rejected';
                    $seller->save();
                }
                if($kyc_details->save()){
                    flash(__('GST Proof Rejected successfully!'))->success();
                }
            }
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'cin_proof_accpeted'){
                $kyc_details->cin_verified = 1;
                $kyc_details->seller_id = $request->route('id');
                if($kyc_details->save()){
                    flash(__('CIN Proof Verified successfully!'))->success();
                }
            }
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'cin_proof_rejected'){
                $kyc_details->cin_verified = 0;
                $kyc_details->seller_id = $seller_id;
                $seller = Seller::find($seller_id);
                if(!empty($seller)){
                    $seller->kyc_status = 'rejected';
                    $seller->save();
                }
                if($kyc_details->save()){
                    flash(__('CIN Proof Rejected successfully!'))->success();
                }
            }
        }else{
            $kyc = new Sellerkyc;            
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'aadhar_proof_accpeted'){
                $kyc->aadhar_verified = 1;
                $kyc->aadhar_pre_verified = 1;
                $kyc->seller_id = $request->route('id');
                if($kyc->save()){
                    flash(__('Aadhar Proof Verified successfully!'))->success();
                }
            }
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'aadhar_proof_rejected'){
                $kyc->aadhar_verified = 0;
                $kyc->aadhar_pre_verified = 0;
                $kyc->seller_id = $seller_id;
                $seller = Seller::find($seller_id);
                if(!empty($seller)){
                    $seller->kyc_status = 'rejected';
                    $seller->save();
                }
                if($kyc->save()){
                    flash(__('Aadhar Proof Rejected successfully!'))->success();
                }
            }            
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'pan_proof_accpeted'){
                $kyc->pan_verified = 1;
                $kyc->pan_pre_verified = 1;
                $kyc->seller_id = $request->route('id');
                if($kyc->save()){
                    flash(__('Aadhar Proof Verified successfully!'))->success();
                }
            }
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'pan_proof_rejected'){
                $kyc->pan_verified = 0;
                $kyc->pan_pre_verified = 0;
                $kyc->seller_id = $seller_id;
                $seller = Seller::find($seller_id);
                if(!empty($seller)){
                    $seller->kyc_status = 'rejected';
                    $seller->save();
                }
                if($kyc->save()){
                    flash(__('PAN Proof Rejected successfully!'))->success();
                }
            }            
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'gst_proof_accpeted'){
                $kyc->gst_verified = 1;
                $kyc->seller_id = $request->route('id');
                if($kyc->save()){
                    flash(__('GST Proof Verified successfully!'))->success();
                }
            }
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'gst_proof_rejected'){
                $kyc->gst_verified = 0;
                $kyc->seller_id = $seller_id;
                $seller = Seller::find($seller_id);
                if(!empty($seller)){
                    $seller->kyc_status = 'rejected';
                    $seller->save();
                }
                if($kyc->save()){
                    flash(__('GST Proof Rejected successfully!'))->success();
                }
            }             
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'cin_proof_accpeted'){
                $kyc->cin_verified = 1;
                $kyc->seller_id = $request->route('id');
                if($kyc->save()){
                    flash(__('CIN Proof Verified successfully!'))->success();
                }
            }
            if(isset($inputs['kyc_status']) && $inputs['kyc_status'] == 'cin_proof_rejected'){
                $kyc->cin_verified = 0;
                $kyc->seller_id = $seller_id;
                $seller = Seller::find($seller_id);
                if(!empty($seller)){
                    $seller->kyc_status = 'rejected';
                    $seller->save();
                }
                if($kyc->save()){
                    flash(__('CIN Proof Rejected successfully!'))->success();
                }
            }           
        }
        return redirect()->route('sellers.show_verification_request', $seller_id);
    } 
    public function kycStatus(Request $request) {
        $inputs = $request->all();
        $seller_id = $request->route('id');
        $seller = Seller::find($seller_id);
        $shop = Shop::where('user_id', $seller->user_id)->first();
        $account = SellerAccountTypeMapping::where('seller_id', $seller->id)->first();
        $account_type = AccountType::where('id', $account->account_type_id)->first();
        $seller_kind_1 = array('individuals', 'sole proprietors', 'freelancers', 'consultants');
        $seller_kind_2 = array('registered business/company', 'authorized reseller', 'partnership', 'trading company', 'ngo');
        $kyc_status = SellerKyc::where('seller_id', $seller_id)->first();
        if($inputs['kyc_status'] == 'verified'){
            if(in_array($account_type->account_type, $seller_kind_1) && strtolower($shop->country) != 'india'){
                if($kyc_status->age_proof_verified == 0 || $kyc_status->address_proof_verified == 0){
                    flash(__('Kindly verify all the documents first!'))->warning();
                    return back();                    
                }
            }
            if(in_array($account_type->account_type, $seller_kind_2) && strtolower($shop->country) != 'india'){
                if($kyc_status->age_proof_verified == 0 || 
                   $kyc_status->address_proof_verified == 0 ||
                   $kyc_status->tax_verified == 0 ||
                   $kyc_status->business_verified == 0
                  ){
                    flash(__('Kindly verify all the documents first!'))->warning();
                    return back();                    
                }
            }
            if(in_array($account_type->account_type, $seller_kind_1) && strtolower($shop->country) == 'india'){
                if($kyc_status->aadhar_verified == 0 || $kyc_status->pan_verified == 0){
                    flash(__('Kindly verify all the documents first!'))->warning();
                    return back();                    
                }
            }
            if(in_array($account_type->account_type, $seller_kind_2) && strtolower($shop->country) == 'india'){
                if($kyc_status->aadhar_verified == 0 || 
                   $kyc_status->pan_verified == 0 ||
                   $kyc_status->gst_verified == 0 ||
                   $kyc_status->cin_verified == 0
                  ){
                    flash(__('Kindly verify all the documents first!'))->warning();
                    return back();                    
                }
            }
        }
        if(!empty($seller)){
            $seller->kyc_status = $inputs['kyc_status'];
        }
        if($seller->save()){
            flash(__('Kyc Status Updated Successfully!'))->success();
            return back();            
        }
    }
}
