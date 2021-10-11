<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use Hash;
use App\Category;
use App\FlashDeal;
use App\Brand;
use App\SubCategory;
use App\SubSubCategory;
use App\Product;
use App\PickupPoint;
use App\CustomerPackage;
use App\CustomerProduct;
use App\User;
use App\Country;
use App\Seller;
use App\SellerPaymentSetting;
use App\AccountType;
use App\Domain;
use App\SellerAccountTypeMapping;
use App\Shop;
use App\Color;
use App\Order;
use App\Currency;
use App\ShippingSetup;
use App\BusinessSetting;
use App\Http\Controllers\SearchController;
use ImageOptimizer;
use Cookie;
use Illuminate\Support\Str;
use App\Mail\SecondEmailVerifyMailManager;
use Mail;
use Illuminate\Support\Facades\Log as Log;
use App\ProductStock;
use App\Language;
use DB;
use CoreComponentRepository;
use App\Notifications\EmailVerificationNotification;

class Steps extends Controller
{
    public function getRegistration(){
        
        return view('frontend.register.reg_st1');
    }

    // reg_1/basic-info
    public function getBasicInfo() {
        $user = Auth::user();
        if($user->email_verified_at == null){
            $user->notify(new EmailVerificationNotification());
            flash(__('Kindly Check Your Mail To Verify Your Email Address'))->success();
            return back();
        }
        $shop = Auth::user()->shop;
        return view('frontend.register.step1_reg_st1', compact('shop'));        
    }

    // reg_1/basic-info POST
    public function update(Request $request, $id){
        $shop = Shop::find($id);
        if($request->has('name') && $request->has('address')){
            $shop->name = $request->name;
            if ($request->has('shipping_cost')) {
                $shop->shipping_cost = $request->shipping_cost;
            }
            $shop->address = $request->address;
            $shop->slug = preg_replace('/\s+/', '-', $request->name).'-'.$shop->id;

            $shop->meta_title = $request->meta_title;
            $shop->meta_description = $request->meta_description;
            $shop->company_name = $request->company_name;
            if($request->hasFile('logo')){
                $shop->logo = $request->logo->store('uploads/shop/logo');
            }

            if ($request->has('pick_up_point_id')) {
                $shop->pick_up_point_id = json_encode($request->pick_up_point_id);
            }
            else {
                $shop->pick_up_point_id = json_encode(array());
            }
        }
        if($shop->save()){
            flash(translate('Your Shop has been updated successfully!'))->success();
            return redirect()->route('steps.home-info');
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    // reg_1/home-info
    public function getHomeInfo() {
        $shop = Auth::user()->shop;
        return view('frontend.register.step2_reg_st1', compact('shop'));   
    }

    //reg_1/home-info POST
    public function postHomeInfo(Request $request, $id){

        $messages = [
            'sliders.dimensions' => 'Thumbnail image must be 1400 x 400'
        ];
        $this->validate($request, [
            'sliders' => 'dimensions:max_width=1400,max_height=400',
            'sliders' => 'array'
        ], $messages);

        $shop = Shop::find($id);
        if($request->has('previous_sliders')){
            $sliders = $request->previous_sliders;
        }
        else{
            $sliders = array();
        }
        if($request->hasFile('sliders')){
            foreach ($request->sliders as $key => $slider) {
                array_push($sliders, $slider->store('uploads/shop/sliders'));
            }
        }
        $shop->home_text = $request->home_text;
        $shop->sliders = json_encode($sliders);
        if($shop->save()){
            flash(translate('Your Shop has been updated successfully!'))->success();
            return redirect()->route('steps.about-info');
        }
        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    //reg_1/about-info
    public function getAboutInfo() {
        $shop = Auth::user()->shop;
        return view('frontend.register.step3_reg_st1', compact('shop'));   
    }

    //reg_1/about-info POST
    public function postAboutInfo(Request $request, $id){
            $shop = Shop::find($id);
            
            if($request->has('about')){
                $shop->about = $request->about;
    
            if($shop->save()){
                flash(translate('Your Shop has been updated successfully!'))->success();
                return redirect()->route('steps.policy');
            }
    
            flash(translate('Sorry! Something went wrong.'))->error();
            return back();
        }
    }

    //reg_1/policy-info
    public function getPolicyInfo() {
        $shop = Auth::user()->shop;
        return view('frontend.register.step4_reg_st1', compact('shop'));   
    }

    //reg_1/policy-info POST
    public function postPolicyInfo(Request $request, $id) {
        $shop = Shop::find($id);
        
        if($shop->seller_type == 'services'){
            if($request->has('refund_policy') && $request->has('payment_policy')){
                $shop->refund_policy = $request->refund_policy;
                $shop->payment_policy = $request->payment_policy;
                $shop->step_1 = 'complete';
            if($shop->save()){
                flash(translate('Your Shop has been updated successfully!'))->success();
                return redirect()->route('steps.kyc');
                }
    
                flash(translate('Sorry! Something went wrong.'))->error();
                return back();
            }
        }
        elseif($shop->seller_type == 'goods' || $shop->seller_type == 'both'){
            if($request->has('refund_policy') && $request->has('shipping_policy') && $request->has('payment_policy')){
                $shop->refund_policy = $request->refund_policy;
                $shop->shipping_policy = $request->shipping_policy;
                $shop->payment_policy = $request->payment_policy;
                $shop->step_1 = 'complete';
            if($shop->save()){
                flash(translate('Your Shop has been updated successfully!'))->success();
                return redirect()->route('steps.kyc');
                }
    
                flash(translate('Sorry! Something went wrong.'))->error();
                return back();
            }
        }
    }

    // reg_2/kyc
    public function getKyc(Request $request){
        $sellerKYC = Seller::where('user_id', Auth::user()->id)->first();
        if($sellerKYC->kyc_status == 'submitted' || $sellerKYC->kyc_status == 'verified'){
            return redirect()->route('steps.payments');
        }
        $this->mail_callback($request);
        if(Auth::user()->user_type == 'customer'){
            return view('frontend.customer.profile');
        }
        elseif(Auth::user()->user_type == 'seller'){
            $user = Auth::user();
            $seller = $user->seller;
            $kyc_status = $seller->kyc_status;
            $query = Shop::where('user_id',$seller->user_id)->first('country');
            $country = $query['country'];
            $account_type = AccountType::get('account_type');
            $account_type = json_decode($account_type, true);
            return view('frontend.register.reg_st2', compact('kyc_status','country','account_type'));
        }
    }

    //reg_2/kyc POST
    public function sellerKyc(Request $request){
        $response['status'] = 0;
        $response['message'] = 'Something went wrong!';
        $inputs = $request->all();
        $user = Auth::user();
        $shop = Shop::where('user_id', $user->id)->first();
        if($user->user_type == 'seller'){
            if(!empty($inputs)){
                $seller = $user->seller;
                $seller->kyc_status = 'submitted';
                if(isset($inputs['aadhar']) && $inputs['aadhar'] = $request->hasFile('aadhar')) {

                    $inputs['aadhar'] = $request->file('aadhar') ;

                    $fileName = $inputs['aadhar']->getClientOriginalName() ;
                    
                    $destinationPath = public_path().'/uploads/' ;
                    $inputs['aadhar']->move($destinationPath,$fileName);
                    $seller->aadhar_img = $fileName ;
                }
                if(isset($inputs['pan']) && $inputs['pan'] = $request->hasFile('pan')) {

                    $inputs['pan'] = $request->file('pan') ;            
                    $fileName = $inputs['pan']->getClientOriginalName() ;
                    $destinationPath = public_path().'/uploads/' ;
                    $inputs['pan']->move($destinationPath,$fileName);
                    $seller->pan_img = $fileName ;
                }
                if(isset($inputs['aadhar_number'])){
                    $seller->aadhar_number = \Arr::get($inputs,'aadhar_number','');                    
                }
                if(isset($inputs['pan_number'])){
                    $seller->pan_number = \Arr::get($inputs,'pan_number','');                  
                }
                if(isset($inputs['gst'])){
                    $seller->gst_number = \Arr::get($inputs,'gst','');                  
                }
                if(isset($inputs['cin'])){
                    $seller->cin_number = \Arr::get($inputs,'cin','');                  
                }
                $seller_id = Seller::where('user_id',$user->id)->first('id');
                $seller_id = $seller_id->id;
                $account_id = AccountType::where('account_type',$inputs['account_type'])->first('id');
                $account_id = $account_id->id;
                if(!empty($account_id) && !empty($seller_id)){
                    $id = SellerAccountTypeMapping::where('seller_id', $seller_id)->first('id');
                    if(!empty($id)){
                        $id = $id->id;
                        $account_type = SellerAccountTypeMapping::find($id);
                        if($account_type){
                            $account_type->account_type_id = $account_id;
                            $account_type->seller_id = $seller_id;  
                            $account_type->save();                      
                        }
                    }else{
                        $account_mapping = new SellerAccountTypeMapping;
                        $account_mapping->account_type_id = !empty($account_id) ? $account_id : '';
                        $account_mapping->seller_id = !empty($seller_id) ? $seller_id : '';
                        $account_mapping->save();
                    }
                }
                if($seller->save()){
                    $shop->step_2 = 'complete';
                    $shop->save();
                    flash(__('Your Documents has been submitted successfully!'))->success();
                    return redirect()->route('steps.payments');
                }else{
                    flash(__('Something went wrong!'))->warning();
                    return back();
                }
            }
        }else{
            flash(__('OOps! Something went wrong!'))->warning();
            return back();
        }
    }

    public function sellerKycNonIndia(Request $request){
        $response['status'] = 0;
        $response['message'] = 'Something went wrong!';
        $inputs = $request->all();
        $user = Auth::user();
        $shop = Shop::where('user_id', $user->id)->first();
        if($user->user_type == 'seller'){
            if(!empty($inputs)){
                $seller = $user->seller;
                $seller->kyc_status = 'submitted';
                if($inputs['tax'] = $request->hasFile('tax')) {

                    $inputs['tax'] = $request->file('tax') ;

                    $fileName = $inputs['tax']->getClientOriginalName() ;
                    
                    $destinationPath = public_path().'/uploads/' ;
                    $inputs['tax']->move($destinationPath,$fileName);
                    $seller->tax_proof_img = $fileName ;
                }
                if($inputs['age'] = $request->hasFile('age')) {

                    $inputs['age'] = $request->file('age') ;            
                    $fileName = $inputs['age']->getClientOriginalName() ;
                    $destinationPath = public_path().'/uploads/' ;
                    $inputs['age']->move($destinationPath,$fileName);
                    $seller->age_proof_img = $fileName ;
                }
                if($inputs['address'] = $request->hasFile('address')) {

                    $inputs['address'] = $request->file('address') ;            
                    $fileName = $inputs['address']->getClientOriginalName() ;
                    $destinationPath = public_path().'/uploads/' ;
                    $inputs['address']->move($destinationPath,$fileName);
                    $seller->address_proof_img = $fileName ;
                }
                $seller->business_proof = \Arr::get($inputs,'business_proof','');
                $seller_id = Seller::where('user_id',$user->id)->first('id');
                $seller_id = $seller_id->id;
                $account_id = AccountType::where('account_type',$inputs['account_type'])->first('id');
                $account_id = $account_id->id;
                if(!empty($account_id) && !empty($seller_id)){
                    $id = SellerAccountTypeMapping::where('seller_id', $seller_id)->first('id');
                    if(!empty($id)){
                        $id = $id->id;
                        $account_type = SellerAccountTypeMapping::find($id);
                        if($account_type){
                            $account_type->account_type_id = $account_id;
                            $account_type->seller_id = $seller_id;  
                            $account_type->save();                      
                        }
                    }else{
                        $account_mapping = new SellerAccountTypeMapping;
                        $account_mapping->account_type_id = !empty($account_id) ? $account_id : '';
                        $account_mapping->seller_id = !empty($seller_id) ? $seller_id : '';
                        $account_mapping->save();
                    }
                }
                if($seller->save()){
                    $seller_kyc = new \App\SellerKyc;
                    $seller_kyc->seller_id = $seller->id;
                    $seller_kyc->save();
                    $shop->step_2 = 'complete';
                    $shop->save();
                    flash(__('Your Documents has been submitted successfully!'))->success();
                    return redirect()->route('steps.payments');
                }else{
                    flash(__('Something went wrong!'))->warning();
                    return back();
                }
            }
        }else{
                flash(__('Something went wrong!'))->warning();
                return back();
        }
    }

    // reg_3/payment-info
    public function getPaymentInfo(){
        if(Auth::user()->user_type == 'seller'){
            $seller_id = Auth::user()->seller->id;
            if(!empty($seller_id)){
                $payment = SellerPaymentSetting::where('seller_id', $seller_id)->first();
                if(!empty($payment->paypal_key) && !empty($payment->razorpay_key) && !empty($payment->stripe_key) && !empty($payment->instamojo_key)){
                    return redirect()->route('steps.shipping');
                }
            }
            if(empty($payment)){
                $payment = new SellerPaymentSetting;
                $payment->paypal_mid = '';
                $payment->paypal_key = '';
                $payment->paypal_email = '';
                $payment->razorpay_key = '';
                $payment->razorpay_secret = '';
                $payment->stripe_key = '';
                $payment->stripe_secret = '';
                $payment->instamojo_key = '';
                $payment->instamojo_token = '';
                $payment->payment_status = 0;
                $payment->razorpay_status = 0;
                $payment->stripe_status = 0;
                $payment->instamojo_status = 0;
            }
            return view('frontend.register.reg_st3', compact('payment'));
        }
    }

    // reg_3/payment-info POST (paypal)
    public function postPaymentInfo(Request $request){    
        if(Auth::user()->user_type == 'customer'){
            return view('frontend.customer.profile');
        }
        $inputs = $request->all();        
        $seller_id = Auth::user()->seller->id;
        $id = SellerPaymentSetting::where('seller_id', $seller_id)->first('id');
        $seller = Seller::where('id', $seller_id)->first();
        $user = User::where('id', $seller->user_id)->first();
        $shop_data = Shop::where('user_id', $user->id)->first();
        $shop = Shop::find($shop_data->id);
        if(!empty($id)){
            $id = $id->id;            
            $seller_payment = SellerPaymentSetting::find($id);
        if(!empty($inputs) && !empty($seller_payment)){
            $seller_payment->seller_id = !empty($seller_id) ? $seller_id : '';
            $seller_payment->paypal_mid = !empty($inputs['mid']) ? $inputs['mid'] : '';
            $seller_payment->paypal_key = !empty($inputs['key']) ? $inputs['key'] : '';
            $seller_payment->paypal_email = !empty($inputs['email']) ? $inputs['email'] : '';
            if(isset($inputs['payment_status'])){
                $seller_payment->payment_status = !empty($inputs['payment_status']) ? $inputs['payment_status'] : 1;
            }else{
                $seller_payment->payment_status = '0';
            }             
            if($seller_payment->save()){
                $shop->step_3 = 'complete';
                $shop->save();
                flash(__('Your Payment setup has been completed successfully!'))->success();
                return back();
            }
        }
        }else{
            $seller_payment = new SellerPaymentSetting;
            $seller_payment->seller_id = !empty($seller_id) ? $seller_id : '';
            $seller_payment->paypal_mid = !empty($inputs['mid']) ? $inputs['mid'] : '';
            $seller_payment->paypal_key = !empty($inputs['key']) ? $inputs['key'] : '';
            $seller_payment->paypal_email = !empty($inputs['email']) ? $inputs['email'] : '';
            $seller_payment->payment_status = !empty($inputs['payment_status']) ? $inputs['payment_status'] : '';
            
            if($seller_payment->save()){
                flash(__('Your Payment setup has been completed successfully!'))->success();
                return back();
            }
        }        
    }

    // reg_3/razorpay POST
    public function postRazorpaySetup(Request $request){
        if(Auth::user()->user_type == 'customer'){
            return view('frontend.customer.profile');
        }
        $inputs = $request->all();                
        $seller_id = Auth::user()->seller->id;
        $id = SellerPaymentSetting::where('seller_id', $seller_id)->first('id');
        $shop_data = Shop::where('user_id', Auth::user()->id)->first();
        $shop = Shop::find($shop_data->id);
        if(!empty($id)){
            $id = $id->id;            
            $seller_payment = SellerPaymentSetting::find($id);
        if(!empty($inputs) && !empty($seller_payment)){
            $seller_payment->seller_id = !empty($seller_id) ? $seller_id : '';
            //For Razor Pay
            $seller_payment->razorpay_key = isset($inputs['razorpay_key']) ? $inputs['razorpay_key'] : '';
            $seller_payment->razorpay_secret = isset($inputs['razorpay_secret']) ? $inputs['razorpay_secret'] : '';
            if(isset($inputs['razorpay_payment_status'])){
                $seller_payment->razorpay_status = isset($inputs['razorpay_payment_status']) ? $inputs['razorpay_payment_status'] : 1;
            }else{
                $seller_payment->razorpay_status = '0';
            }             
            if($seller_payment->save()){
                $shop->step_3 = 'complete';
                $shop->save();
                flash(__('Your Payment setup has been completed successfully!'))->success();
                return back();
            }
        }
        }else{
            $seller_payment = new SellerPaymentSetting;
            $seller_payment->seller_id = !empty($seller_id) ? $seller_id : '';
            $seller_payment->razorpay_key = isset($inputs['razorpay_key']) ? $inputs['razorpay_key'] : '';
            $seller_payment->razorpay_secret = isset($inputs['razorpay_secret']) ? $inputs['razorpay_secret'] : '';
            $seller_payment->razorpay_status = isset($inputs['razorpay_payment_status']) ? $inputs['razorpay_payment_status'] : '';
            
            if($seller_payment->save()){
                flash(__('Your Payment setup has been completed successfully!'))->success();
                return back();
            }
        }  
    }
    
    // reg_3/stripe POST
    public function postStripeSetup(Request $request){
        if(Auth::user()->user_type == 'customer'){
            return view('frontend.customer.profile');
        }
        $inputs = $request->all();        
        $seller_id = Auth::user()->seller->id;
        $shop_data = Shop::where('user_id', Auth::user()->id)->first();
        $shop = Shop::find($shop_data->id);
        $id = SellerPaymentSetting::where('seller_id', $seller_id)->first('id');
        if(!empty($id)){
            $id = $id->id;            
            $seller_payment = SellerPaymentSetting::find($id);
        if(!empty($inputs) && !empty($seller_payment)){
            $seller_payment->seller_id = !empty($seller_id) ? $seller_id : '';
            //For Stripe pay
            $seller_payment->stripe_key = isset($inputs['stripe_key']) ? $inputs['stripe_key'] : '';
            $seller_payment->stripe_secret = isset($inputs['stripe_secret']) ? $inputs['stripe_secret'] : '';
            if(isset($inputs['stripe_payment_status'])){
                $seller_payment->stripe_status = isset($inputs['stripe_payment_status']) ? $inputs['stripe_payment_status'] : 1;
            }else{
                $seller_payment->stripe_status = '0';
            }             
            if($seller_payment->save()){
                $shop->step_3 = 'complete';
                $shop->save();
                flash(__('Your Payment setup has been completed successfully!'))->success();
                return back();
            }
        }
        }else{
            $seller_payment = new SellerPaymentSetting;
            $seller_payment->seller_id = !empty($seller_id) ? $seller_id : '';
            $seller_payment->stripe_key = isset($inputs['stripe_key']) ? $inputs['stripe_key'] : '';
            $seller_payment->stripe_secret = isset($inputs['stripe_secret']) ? $inputs['stripe_secret'] : '';
            $seller_payment->stripe_status = isset($inputs['stripe_payment_status']) ? $inputs['stripe_payment_status'] : '';
            
            if($seller_payment->save()){
                flash(__('Your Payment setup has been completed successfully!'))->success();
                return back();
            }
        }  
    }

    // reg_3/instamojo POST
    public function postInstamojoSetup(Request $request){
        if(Auth::user()->user_type == 'customer'){
            return view('frontend.customer.profile');
        }
        $inputs = $request->all();    
        $seller_id = Auth::user()->seller->id;
        $shop_data = Shop::where('user_id', Auth::user()->id)->first();
        $shop = Shop::find($shop_data->id);
        $id = SellerPaymentSetting::where('seller_id', $seller_id)->first('id');
        if(!empty($id)){
            $id = $id->id;            
            $seller_payment = SellerPaymentSetting::find($id);
        if(!empty($inputs) && !empty($seller_payment)){
            $seller_payment->seller_id = !empty($seller_id) ? $seller_id : '';
            //For Instamojo
            $seller_payment->instamojo_key = isset($inputs['instamojo_key']) ? $inputs['instamojo_key'] : '';
            $seller_payment->instamojo_token = isset($inputs['instamojo_token']) ? $inputs['instamojo_token'] : '';
            if(isset($inputs['instamojo_payment_status'])){
                $seller_payment->instamojo_status = isset($inputs['instamojo_payment_status']) ? $inputs['instamojo_payment_status'] : 1;
            }else{
                $seller_payment->instamojo_status = '0';
            }             
            if($seller_payment->save()){
                $shop->step_3 = 'complete';
                $shop->save();
                flash(__('Your Payment setup has been completed successfully!'))->success();
                return back();
            }
        }
        }else{
            $seller_payment = new SellerPaymentSetting;
            $seller_payment->seller_id = !empty($seller_id) ? $seller_id : '';
            $seller_payment->instamojo_key = isset($inputs['instamojo_key']) ? $inputs['instamojo_key'] : '';
            $seller_payment->instamojo_token = isset($inputs['instamojo_token']) ? $inputs['instamojo_token'] : '';
            $seller_payment->instamojo_status = isset($inputs['instamojo_payment_status']) ? $inputs['instamojo_payment_status'] : '';
            
            if($seller_payment->save()){
                flash(__('Your Payment setup has been completed successfully!'))->success();
                return back();
            }
        }  
    }


    // reg_4/shipping 
    public function getShippingSetup(Request $request){
        $shop = Auth::user()->shop;
        //retriving the currency price from session 
        $code = \Session::get('currency_code');
        if($code == 'Rupee'){
            $country = 'india';            
        }else{
            $country = 'USA';
        }
        if($shop->seller_type == 'services'){
            $shop->step_4 == 'complete';
            $shop->save();
            return redirect()->route('steps.domain');
        }elseif($shop->seller_type == 'goods' || $shop->seller_type == 'both'){
            $inputs = $request->all();
            $seller_id = Auth::user()->seller->id;
            $shipping = ShippingSetup::where('seller_id',$seller_id)->get();
            
            $user = Auth::user();
            $shipping = \App\ShippingSetup::where('seller_id', $user->seller->id)->get();   
            $array = [];
            foreach($shipping as $ship){
                array_push($array, $ship->shipping_type);
            }  
            if(sizeof($array) == 4){ 
                $shop->step_4 = 'complete';
                $shop->save();
                return redirect()->route('steps.domain');
            }
            if(!empty($shipping)){
                return view('frontend.register.reg_st4', compact('shipping', 'country'));
            }
            return view('frontend.register.reg_st4');
        }
    }

    // reg_4/shipping POST
    public function postShippingSetup(Request $request) {

        if(Auth::user()->user_type == 'customer'){
            return view('frontend.customer.profile');
        }

        
        //retriving the currency price from session 
        $currency = Currency::where('code', 'Rupee')->first();
        $exchange_rate = $currency->exchange_rate;

        $response['status'] = 0;
        $response['status'] = 'Something went wrong!';
        $inputs = $request->all();
        $seller_id = Auth::user()->seller->id;
        if(!empty($inputs)){
            if($inputs['tab'] == NULL){ 
                $response['stauts'] = 0;
                $response['message'] = 'Please Select Shipping Type';
                return json_encode($response);
            }
            if($inputs['rate_100'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for 0-100gm';
                return json_encode($response);
            }
            if($inputs['rate_500'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for 101-500gm';
                return json_encode($response);
            }
            if($inputs['rate_1000'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for 501-1000gm';
                return json_encode($response);
            }
            if($inputs['rate_1500'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for 1001-1500gm';
                return json_encode($response);
            }
            if($inputs['rate_2000'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for 1501-2000gm';
                return json_encode($response);
            }
            if($inputs['rate_2500'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for 2001-2500gm';
                return json_encode($response);
            }
            if($inputs['rate_3000'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for 2501-3000gm';
                return json_encode($response);
            }
            if($inputs['rate_3500'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for 3001-3500gm';
                return json_encode($response);
            }
            if($inputs['rate_4000'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for 3501-4000gm';
                return json_encode($response);
            }
            if($inputs['rate_4500'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for 4001-4500gm';
                return json_encode($response);
            }
            if($inputs['rate_5000'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for 4501-5000gm';
                return json_encode($response);
            }
            if($inputs['more_than_5000'] == NULL){                
                $response['stauts'] = 0;
                $response['message'] = 'Please enter shipping price for more than 5000gm';
                return json_encode($response);
            }
            $shipping_data = ShippingSetup::where('seller_id', $seller_id)
                    ->where('shipping_type', $inputs['tab'])
                    ->first();
            
            if(!empty($shipping_data) && !empty($inputs['tab'])){
                if($shipping_data->shipping_type == $inputs['tab']){
                    $response['stauts'] = 0;
                    $response['message'] = 'Shipping charges for '. $inputs['tab']. ' already existed.';                    
                    return json_encode($response);
                }
            }else{
                $shipping = new ShippingSetup;
                $shipping->seller_id = !empty($seller_id) ? $seller_id : '';
                $shipping->shipping_type = !empty($inputs['tab']) ? $inputs['tab'] : '';
                $rate = [
                    'rate_100' => !empty($inputs['rate_100']) ? bcdiv($inputs['rate_100'], $exchange_rate, 2) : '',
                    'rate_500' => !empty($inputs['rate_500']) ? bcdiv($inputs['rate_500'], $exchange_rate, 2) : '',
                    'rate_1000' => !empty($inputs['rate_1000']) ? bcdiv($inputs['rate_1000'], $exchange_rate, 2) : '',
                    'rate_1500' => !empty($inputs['rate_1500']) ? bcdiv($inputs['rate_1500'], $exchange_rate, 2) : '',
                    'rate_2000' => !empty($inputs['rate_2000']) ? bcdiv($inputs['rate_2000'], $exchange_rate, 2) : '',
                    'rate_2500' => !empty($inputs['rate_2500']) ? bcdiv($inputs['rate_2500'], $exchange_rate, 2) : '',
                    'rate_3000' => !empty($inputs['rate_3000']) ? bcdiv($inputs['rate_3000'], $exchange_rate, 2) : '',
                    'rate_3500' => !empty($inputs['rate_3500']) ? bcdiv($inputs['rate_3500'], $exchange_rate, 2) : '',
                    'rate_4000' => !empty($inputs['rate_4000']) ? bcdiv($inputs['rate_4000'], $exchange_rate, 2) : '',
                    'rate_4500' => !empty($inputs['rate_4500']) ? bcdiv($inputs['rate_4500'], $exchange_rate, 2) : '',
                    'rate_5000' => !empty($inputs['rate_5000']) ? bcdiv($inputs['rate_5000'], $exchange_rate, 2) : '',
                    'more_than_5000' => !empty($inputs['more_than_5000']) ? bcdiv($inputs['more_than_5000'], $exchange_rate, 2) : '',
                ];
                $rate = json_encode($rate);
                $shipping->rate_weight = !empty($rate) ? $rate : '';
                if($shipping->save()){
                    $response['status'] = 1;
                    $response['message'] = 'Shipping Details Saved Successfully.';
                }                
                return json_encode($response);
            }
        }            
    }
    

    public function postShippingUpdate(Request $request) {  
        if(Auth::user()->user_type == 'customer'){
            return view('frontend.customer.profile');
        }

        //retriving the currency price from session 
        $currency = Currency::where('code', \Session::get('currency_code'))->first();
        $exchange_rate = $currency->exchange_rate;

        $response['status'] = 0;
        $response['status'] = 'Something went wrong!';
        $inputs = $request->all();
        $seller_id = Auth::user()->seller->id;
        if(isset($inputs['shipping']) && isset($inputs['rate_array'])){
            $shipping_info = ShippingSetup::where('seller_id', $seller_id)->where('shipping_type', $inputs['shipping'])->first();
            $rate = [
                'rate_100' => !empty($inputs['rate_array']['rate_100']) ? bcdiv($inputs['rate_array']['rate_100'], $exchange_rate, 2) : '',
                'rate_500' => !empty($inputs['rate_array']['rate_500']) ? bcdiv($inputs['rate_array']['rate_500'], $exchange_rate, 2) : '',
                'rate_1000' => !empty($inputs['rate_array']['rate_1000']) ? bcdiv($inputs['rate_array']['rate_1000'], $exchange_rate, 2) : '',
                'rate_1500' => !empty($inputs['rate_array']['rate_1500']) ? bcdiv($inputs['rate_array']['rate_1500'], $exchange_rate, 2) : '',
                'rate_2000' => !empty($inputs['rate_array']['rate_2000']) ? bcdiv($inputs['rate_array']['rate_2000'], $exchange_rate, 2) : '',
                'rate_2500' => !empty($inputs['rate_array']['rate_2500']) ? bcdiv($inputs['rate_array']['rate_2500'], $exchange_rate, 2) : '',
                'rate_3000' => !empty($inputs['rate_array']['rate_3000']) ? bcdiv($inputs['rate_array']['rate_3000'], $exchange_rate, 2) : '',
                'rate_3500' => !empty($inputs['rate_array']['rate_3500']) ? bcdiv($inputs['rate_array']['rate_3500'], $exchange_rate, 2) : '',
                'rate_4000' => !empty($inputs['rate_array']['rate_4000']) ? bcdiv($inputs['rate_array']['rate_4000'], $exchange_rate, 2) : '',
                'rate_4500' => !empty($inputs['rate_array']['rate_4500']) ? bcdiv($inputs['rate_array']['rate_4500'], $exchange_rate, 2) : '',
                'rate_5000' => !empty($inputs['rate_array']['rate_5000']) ? bcdiv($inputs['rate_array']['rate_5000'], $exchange_rate, 2) : '',
                'more_than_5000' => !empty($inputs['rate_array']['more_than_5000']) ? bcdiv($inputs['rate_array']['more_than_5000'], $exchange_rate, 2) : '',
            ];
            $rate = json_encode($rate);
            $shipping_info->rate_weight = !empty($rate) ? $rate : '';
            $shipping_info->save();
            $response['status'] = 1;
            $response['message'] = 'Shipping Details Updated Successfully.';
            return json_encode($response);
        }  
    }

    //  reg_5/domain
    public function stepGetDomainSetup() {
        $shop = Shop::where('user_id', Auth::user()->id)->first();
        if(!empty($shop->domain)){
            $shop->step_5 = 'complete';
            $shop->save();
            if($shop->seller_type == 'goods' || $shop->seller_type == 'both'){
                return redirect()->route('steps.product');
            }else{
                return redirect()->route('steps.service');
            }
        }
        if(Auth::user()->user_type == 'seller'){
            $user = Auth::user();
            $domain = Shop::where('user_id', $user->id)->first();
            if(empty($domain->domain)){
                $domain = new Shop;
                $domain->domain = '';
            }
            return view('frontend.register.reg_st5', compact('domain'));
        }
    }  

    // reg_5/domain POST
    public function stepPostDomainSetup(Request $request) {
        if(Auth::user()->user_type == 'customer'){
            return view('frontend.customer.profile');
        }
        $inputs = $request->all();        
        $user = Auth::user();
        $shop = Shop::where('user_id', $user->id)->first();
        if(!empty($shop->domain)){            
            $domain = Shop::find($shop->id);
        if(!empty($inputs) && !empty($domain->domain)){
            $domain->domain = !empty(strtolower($inputs['domain'])) ? strtolower($inputs['domain']) : '';
            if($domain->save()){
                $shop->step_5 = 'complete';
                $shop->save();
                flash(__('Your Domain setup has been completed successfully!'))->success();
                if($shop->seller_type == 'goods' || $shop->seller_type == 'services'){
                    return redirect('reg_6/product');
                }else{
                    return redirect('reg_6/service');
                }
            }
        }
        }else{
            $domain = Shop::find($shop->id);
            $domain->domain = !empty(strtolower($inputs['domain'])) ? strtolower($inputs['domain']) : '';
            
            if($domain->save()){
                flash(__('Your Domain setup has been updated successfully!'))->success();
                return redirect('reg_6/product');
            }
        }
    }
    public function mail_callback($request){
        if(!Auth::user()){
            return null;
        }

        if($request->has('new_email_verificiation_code') && $request->has('email')) {
            $verification_code_of_url_param =  $request->input('new_email_verificiation_code');
            $verification_code_of_db = Auth::user()->new_email_verificiation_code;

            if(strcmp($verification_code_of_url_param, $verification_code_of_db) == 0) {
                $user = Auth::user();
                $user->email = $request->input('email');
                $user->save();
                flash('Email Changed successfully')->success();
            }
        }

        return null;

    }

    // reg_6/product
    public function getProduct(){  
        $user = Auth::user();
        $user_product = Product::where('user_id', $user->id)->first();
        if($user_product != ''){
            $shops = Shop::where('user_id', $user->id)->first();
            $is_shop = Shop::find($shops->id);
            if($is_shop){
                $is_shop->step_6 = 'complete';
            }
            if($is_shop->save()){
                return redirect('dashboard');
            }
        }   
        $categories = Category::all();
        $countries = Country::get('name');
        return view('frontend.register.reg_st6', compact('categories', 'countries'));
    }


    // reg_6/product POST
    public function postProduct(Request $request){

        $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
        
        $shops = Shop::where('user_id', Auth::user()->id)->first();
        $product = new Product;
        if(!empty($request->country)){
            $product->country = $request->country;            
        }else{
            flash(translate('Kindly select the country!'))->error();                        
            return back();
        }
        if(!empty($request->weight)){
            $product->weight = $request->weight;            
        }else{
            flash(translate('Kindly select the weight of product!'))->error();                        
            return back();
        }
        if($request->refundable == 'on'){
            $product->return_validity = isset($request->return) ? $request->return : 0;
        }else{
            $product->return_validity = 0;
        }
        $product->name = $request->name;
        $product->product_sku = $request->product_sku;
        $product->added_by = $request->added_by;
        if(Auth::user()->user_type == 'seller'){
            $product->user_id = Auth::user()->id;
        }
        else{
            $product->user_id = \App\User::where('user_type', 'admin')->first()->id;
        }
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->subsubcategory_id = $request->subsubcategory_id;
        $product->brand_id = $request->brand_id;
        $product->current_stock = $request->current_stock;
        $product->barcode = $request->barcode;

        if ($refund_request_addon != null && $refund_request_addon->activated == 1) {
            if ($request->refundable != null) {
                $product->refundable = 1;
            }
            else {
                $product->refundable = 0;
            }
        }

        $photos = array();

        if($request->hasFile('photos')){
            foreach ($request->photos as $key => $photo) {
                $path = $photo->store('uploads/products/photos');
                array_push($photos, $path);
                //ImageOptimizer::optimize(base_path('public/').$path);
            }
            $product->photos = json_encode($photos);
        }

        if($request->hasFile('thumbnail_img')){
            $product->thumbnail_img = $request->thumbnail_img->store('uploads/products/thumbnail');
            //ImageOptimizer::optimize(base_path('public/').$product->thumbnail_img);
        }

        $product->unit = $request->unit;
        $product->min_qty = $request->min_qty;
        $product->tags = implode('|',$request->tags);
        $product->description = $request->description;
        $product->video_provider = $request->video_provider;
        $product->video_link = $request->video_link;
        //retriving the currency price from session 
        $currency = Currency::where('code', 'Rupee')->first();
        $exchange_rate = $currency->exchange_rate;
        if($request->unit_price_ei){
            $product->unit_price = $request->unit_price_ei / $exchange_rate;
        }else{
            $product->unit_price = $request->unit_price_nei;
        }
        // $product->purchase_price = $request->purchase_price;
        $product->tax_type = $request->tax_type;
        if($request->tax_type == 'amount_usd'){
            $product->tax = $request->tax;
        }elseif($request->tax_type == 'amount_inr'){
            $product->tax = $request->tax / $exchange_rate;
        }else{
            $product->tax = $request->tax;
        }
        $product->discount_type = $request->discount_type;
        if($request->discount_type == 'amount_usd'){
            $product->discount = $request->discount;
        }elseif($request->discount_type == 'amount_inr'){
            $product->discount = $request->discount / $exchange_rate;
        }else{
            $product->tax = $request->discount;
        }
        $inputs = $request->all();
        if(!empty($inputs) && isset($inputs['free_shipping']) && $inputs['free_shipping'] == 'on'){
            $product->shipping_type = 'free';
        }
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;

        if($request->hasFile('meta_img')){
            $product->meta_img = $request->meta_img->store('uploads/products/meta');
            //ImageOptimizer::optimize(base_path('public/').$product->meta_img);
        } else {
            $product->meta_img = $product->thumbnail_img;
        }

        if($product->meta_title == null) {
            $product->meta_title = $product->name;
        }

        if($product->meta_description == null) {
            $product->meta_description = $product->description;
        }

        if($request->hasFile('pdf')){
            $product->pdf = $request->pdf->store('uploads/products/pdf');
        }

        $product->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);

        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $product->colors = json_encode($request->colors);
        }
        else {
            $colors = array();
            $product->colors = json_encode($colors);
        }
        //UPDATING REQUEST DATA
        $choice_options = array();
        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_'.$no;
                $my_str = $request[$str];
                foreach($request->$str as $st){
                    $searchForValue = ',';
                    if( strpos($st, $searchForValue) !== false ) {                        
                        $string = explode(',', $st);
                        $str = $string;
                    }else{
                        $str = $my_str;
                    }
                }
                $item['attribute_id'] = $no;
                $item['values'] = $str;
                array_push($choice_options, $item);
            }
        }
        if (!empty($request->choice_no)) {
            $product->attributes = json_encode($request->choice_no);
        }
        else {
            $product->attributes = json_encode(array());
        }

        $product->choice_options = json_encode($choice_options);

        //$variations = array();
        $product->save();
        //combinations start
        $options = array();
        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
            $colors_active = 1;
            array_push($options, $request->colors);
        }

        if($request->has('choice_no')){
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = $request[$name];
                foreach($request->$name as $st){
                    $searchForValue = ',';
                    if( strpos($st, $searchForValue) !== false ) {                        
                        $string = explode(',', $st);
                        $str = $string;
                    }else{
                        $str = $my_str;
                    }
                }
                // array_push($options, explode(',', $my_str));
                array_push($options, $str);
            }
        }
        //Generates the combinations of customer choice options
        $combinations = combinations($options);
        if(count($combinations[0]) > 0){
            $product->variant_product = 1;
            foreach ($combinations as $key => $combination){
                $str = '';
                $vrnt = '';
                foreach ($combination as $key => $item){
                    if($key > 0 ){
                        $str .= '-'.str_replace(' ', '', $item);
                        $vrnt .= ''.str_replace(' ', '', $item);
                    }
                    else{
                        if($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0){
                            $color_name = \App\Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                            $vrnt .= $color_name;
                        }
                        else{
                            $str .= str_replace(' ', '', $item);
                            $vrnt .= str_replace(' ', '', $item);
                        }
                    }
                }

                $product_stock = ProductStock::where('product_id', $product->id)->where('variant', $str)->first();
                if($product_stock == null){
                    $product_stock = new ProductStock;
                    $product_stock->product_id = $product->id;
                }
                $variant_img = array();
                if($request->hasFile($str)){
                    unset($variant_img);
                    $variant_img = array();
                    foreach($request->$str as $key => $img) {
                        $path = $img->store('uploads/products/variants/');
                        array_push($variant_img, $path);
                        $product_stock->variant_img = json_encode($variant_img);
                        if(is_array($vrnt)){
                            sort($vrnt);    
                        }else{
                            $vrnt = str_split($vrnt);
                            sort($vrnt);
                        }
                        $product_stock->variant = implode('', $vrnt);
                        $product_stock->price = $request['price_'.str_replace('.', '_', $str)] / 73;
                        $product_stock->sku = $request['sku_'.str_replace('.', '_', $str)];
                        $product_stock->qty = $request['qty_'.str_replace('.', '_', $str)];
                    }
                    $product_stock->save();
                }
            }
        }
        //combinations end
        foreach (Language::all() as $key => $language) {
            $data = openJSONFile($language->code);
            $data[$product->name] = $product->name;
            saveJSONFile($language->code, $data);
        }
	    if($product->save()){
            $shops->step_6 = 'complete';
            $shops->save();
        }
        flash(translate('Product has been inserted successfully'))->success();
        if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff'){
            return redirect()->route('products.admin');
        }
        else{
            if(\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated){
                $seller = Auth::user()->seller;
                $seller->remaining_uploads -= 1;
                $seller->save();
            }
            return redirect()->route('dashboard');
        }
    }


    // reg_6/services
    public function getServices(){
        
        $user = Auth::user();
        $user_product = Product::where('user_id', $user->id)->first();
        if($user_product != ''){
            $shops = Shop::where('user_id', $user->id)->first();
            $is_shop = Shop::find($shops->id);
            if($is_shop){
                $is_shop->step_6 = 'complete';
            }
            if($is_shop->save()){
                return redirect('dashboard');
            }
        }  
        if(\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated){
            if(Auth::user()->seller->remaining_uploads > 0){
                $categories = Category::where('digital', 1)->get();
                return view('frontend.register.reg_st7', compact('categories'));
            }
            else {
                flash('Upload limit has been reached. Please upgrade your package.')->warning();
                return back();
            }
        }
        $categories = Category::where('digital', 1)->get();
        $countries = Country::get('name');
        return view('frontend.register.reg_st7', compact('categories', 'countries'));
    }













    

    // reg_6/services POST
    public function postServices(Request $request){
        $product = new Product;
        $product->name = $request->name;
        $product->added_by = $request->added_by;
        $product->user_id = Auth::user()->id;
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->subsubcategory_id = $request->subsubcategory_id;
        $product->digital = 1;

        $photos = array();

        if($request->hasFile('photos')){
            foreach ($request->photos as $key => $photo) {
                $path = $photo->store('uploads/products/photos');
                array_push($photos, $path);
                if($key == 0){
                    $product->meta_img = $path;
                }
            }
            $product->photos = json_encode($photos);
        }

        if($request->hasFile('thumbnail_img')){
            $product->thumbnail_img = $request->thumbnail_img->store('uploads/products/thumbnail');
        }

        $product->tags = implode('|',$request->tags);
        $product->description = $request->description;
        // $product->unit_price = convert_inr_to_usd($request->unit_price);
        //retriving the currency price from session 
        $currency = Currency::where('code', 'Rupee')->first();
        $exchange_rate = $currency->exchange_rate;
        if($request->unit_price_ei){
            $product->unit_price = $request->unit_price_ei / $exchange_rate;
        }else{
            $product->unit_price = $request->unit_price_nei;
        }
        // $product->purchase_price = $request->purchase_price;
        $product->tax_type = $request->tax_type;
        if($request->tax_type == 'amount_usd'){
            $product->tax = $request->tax;
        }elseif($request->tax_type == 'amount_inr'){
            $product->tax = $request->tax / $exchange_rate;
        }else{
            $product->tax = $request->tax;
        }
        $product->discount_type = $request->discount_type;
        if($request->discount_type == 'amount_usd'){
            $product->discount = $request->discount;
        }elseif($request->discount_type == 'amount_inr'){
            $product->discount = $request->discount / $exchange_rate;
        }else{
            $product->tax = $request->discount;
        }

        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;

        if($request->hasFile('meta_img')){
            $product->meta_img = $request->meta_img->store('uploads/products/meta');
        }

        if($request->hasFile('file')){
            $product->file_name = $request->file('file')->getClientOriginalName();
            $product->file_path = $request->file('file')->store('uploads/products/digital');
        }

        $product->slug = rand(10000,99999).'-'.preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name));

        $data = openJSONFile('en');
        $data[$product->name] = $product->name;
        saveJSONFile('en', $data);
        if($product->save()){
            flash(translate('Digital Product has been inserted successfully'))->success();
            if(Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff'){
                return redirect()->route('digitalproducts.index');
            }
            else{
                if(\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated){
                    $seller = Auth::user()->seller;
                    $seller->remaining_digital_uploads -= 1;
                    $seller->save();
                }
                return redirect()->route('dashboard');
            }
        }
        else{
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }
}
