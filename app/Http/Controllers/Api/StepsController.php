<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\StepsCollection;
use App\User;
use App\Shop;
use App\Seller;
use App\Currency;
use App\Language;
use App\Product;
use App\ProductStock;
use App\ShippingSetup;
use App\SellerPaymentSetting;
use App\SellerAccountTypeMapping;
use App\AccountType;
use App\BusinessSetting;
use App\Notifications\EmailVerificationNotification;
use Auth;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class StepsController extends Controller
{
    public function beSeller(Request $request)
    {
        $inputs = $request->all();
        if(!empty($inputs)){
            $users = new User;
            $users->name        = !empty($inputs['name']) ? $inputs['name'] : '';
            $users->phone       = !empty($inputs['phone']) ? $inputs['phone'] : '';
            $users->email       = !empty($inputs['email']) ? $inputs['email'] : '';
            $users->user_type   = !empty($inputs['user_type']) ? $inputs['user_type'] : '';
            if($inputs['password'] == $inputs['confirm_password']){
                $users->password = !empty($inputs['password']) ? Hash::make($inputs['password']) : '';
            }
            $otp = rand(1000, 9999);
            $users->otp = $otp;
            Session::put('otp', $otp);
            Session::put('email', $inputs['email']);
            if(BusinessSetting::where('type', 'email_verification')->first()->value != 1){
                $users->email_verified_at = date('Y-m-d H:m:s');
            }
            else {
                $users->notify(new EmailVerificationNotification());
            }
            if($users->save()){
                $seller = new Seller;
                $seller->user_id = $users->id;
                $seller->save();
                $shops = new Shop;
                $shops->name = !empty($inputs['shop_name']) ? $inputs['shop_name'] : '';
                if($request->hasFile('logo')){
                    $shops->logo = $request->logo->store('uploads/shop/logo');
                }else{
                    $shops->logo = '';
                }
                $shops->user_id = $users->id;
                $shops->seller_type = !empty($inputs['seller_type']) ? $inputs['seller_type'] : '';
                $shops->address     = !empty($inputs['address']) ? $inputs['address'] : '';
                $shops->country     = !empty($inputs['country']) ? $inputs['country'] : '';
                $shops->state       = !empty($inputs['state']) ? $inputs['state'] : '';
                $shops->city        = !empty($inputs['city']) ? $inputs['city'] : '';                
                if($shops->save()){
                    return response()->json([
                        'status'  => 'success',
                        'user_id' => $users->id,
                        'message' => 'Seller Account Created Successfully.',
                    ]);                    
                }
            }
        }
    }
    public function verifyOtp(Request $request){
        $inputs = $request->all();
        if(isset($inputs['otp']) && isset($inputs['user_id'])){
            $user = User::findOrFail($inputs['user_id']);
            if($user->otp == $inputs['otp']){
                $user->email_verified_at = Carbon::now();
                $user->save();
                auth()->login($user, true);
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Email Verified Successfully.',
                ]);                
            }
        }
    } 
    public function basicInfo(){
        $shops = Shop::where('user_id', Auth::id());      
        return new StepsCollection($shops->get());
    }
    public function updateBasicInfo(Request $request){
        $shop = Shop::where('user_id', $request->user_id)->first();
        $shop = Shop::find($shop->id);
        if($request->has('name') && $request->has('address')){
            $shop->name = $request->name;
            $shop->address = $request->address;
            $shop->slug = preg_replace('/\s+/', '-', $request->name).'-'.$shop->id;

            $shop->meta_title = $request->meta_title;
            $shop->meta_description = $request->meta_description;
            $shop->company_name = $request->name;
            if($request->hasFile('logo')){
                $shop->logo = $request->logo->store('uploads/shop/logo');
            }
        }
        if($shop->save()){
            return response()->json([
                'status'  => 'success',
                'message' => 'Basic Information saved.',
            ]); 
        }else{
            return response()->json([
                'status'  => 'failed',
                'message' => 'Someting Went Wrong!',
            ]);            
        }
    }
    public function postHomeInfo(Request $request){
        $shop = Shop::where('user_id', $request->user_id)->first();
        $shop = Shop::find($shop->id);
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
            return response()->json([
                'status'  => 'success',
                'message' => 'Basic Information saved.',
            ]);
        }else{
            return response()->json([
                'status'  => 'failed',
                'message' => 'Someting Went Wrong!',
            ]);            
        }
    }
    public function postAboutInfo(Request $request){
            $shop = Shop::where('user_id', $request->user_id)->first();
            $shop = Shop::find($shop->id);
            
            if($request->has('about')){
                $shop->about = $request->about;
    
            if($shop->save()){
                return response()->json([
                    'status'  => 'success',
                    'message' => 'About saved.',
                ]);
            }else{
                return response()->json([
                    'status'  => 'failed',
                    'message' => 'Someting Went Wrong!',
                ]);            
            }
        }
    }
    public function postPolicyInfo(Request $request) {
        $shop = Shop::where('user_id', $request->user_id)->first();
        $shop = Shop::find($shop->id);
        
        if($shop->seller_type == 'services'){
            if($request->has('refund_policy') && $request->has('payment_policy')){
                $shop->refund_policy = $request->refund_policy;
                $shop->payment_policy = $request->payment_policy;
                $shop->step_1 = 'complete';
                if($shop->save()){
                    return response()->json([
                        'status'  => 'success',
                        'message' => 'About saved.',
                    ]);
                }else{
                    return response()->json([
                        'status'  => 'failed',
                        'message' => 'Someting Went Wrong!',
                    ]);            
                }
            }
        }
        elseif($shop->seller_type == 'goods' || $shop->seller_type == 'both'){
            if($request->has('refund_policy') && $request->has('shipping_policy') && $request->has('payment_policy')){
                $shop->refund_policy = $request->refund_policy;
                $shop->shipping_policy = $request->shipping_policy;
                $shop->payment_policy = $request->payment_policy;
                $shop->step_1 = 'complete';
                if($shop->save()){
                    return response()->json([
                        'status'  => 'success',
                        'message' => 'About saved.',
                    ]);
                }else{
                    return response()->json([
                        'status'  => 'failed',
                        'message' => 'Someting Went Wrong!',
                    ]);            
                }
            }
        }
    }
    public function getAccountType(){
        return new StepsCollection(AccountType::get());        
    }

    public function sellerKycIndia(Request $request){
        $response['status'] = 0;
        $response['message'] = 'Something went wrong!';
        $inputs = $request->all();
        $user = User::findOrFail($request->user_id);
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
                    return response()->json([
                        'status'  => 'success',
                        'message' => 'Your Documents has been submitted successfully!',
                    ]);
                }else{
                    return response()->json([
                        'status'  => 'failed',
                        'message' => 'Something Went Wrong!',
                    ]);
                }
            }
        }else{
            return response()->json([
                'status'  => 'failed',
                'message' => 'OOps! Something went wrong!',
            ]);
        }
    }
    public function sellerKycNonIndia(Request $request){
        $response['status'] = 0;
        $response['message'] = 'Something went wrong!';
        $inputs = $request->all();
        $user = User::findOrFail($request->user_id);
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
                    return response()->json([
                        'status'  => 'success',
                        'message' => 'Your Documents has been submitted successfully!',
                    ]);
                }else{
                    return response()->json([
                        'status'  => 'success',
                        'message' => 'Something Went Wrong!',
                    ]);
                }
            }
        }else{
            return response()->json([
                'status'  => 'success',
                'message' => 'Oops!, Something Went Wrong.',
            ]);
        }
    }

    public function getCountry(Request $request){
        $shops = Shop::where('user_id', $request->user_id)->first();
        $country = $shops->country;
        return response()->json([
            'status'  => 'success',
            'data' => $country,
        ]);
    }
 // reg_3/payment-info POST (paypal)
 public function postPaypalSetup(Request $request){    
    $inputs = $request->all();  
    $user = User::findOrFail($request->user_id);      
    $seller_id = $user->seller->id;
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
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Your Payment setup has been completed successfully!',
                ]);
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
            return response()->json([
                'status'  => 'success',
                'message' => 'Your Payment setup has been completed successfully!',
            ]);
        }
    }        
}
// reg_3/razorpay POST
public function postRazorpaySetup(Request $request){
    $inputs = $request->all();                
    $user = User::findOrFail($request->user_id);      
    $seller_id = $user->seller->id;
    $id = SellerPaymentSetting::where('seller_id', $seller_id)->first('id');
    $shop_data = Shop::where('user_id', $user->id)->first();
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
            return response()->json([
                'status'  => 'success',
                'message' => 'Your Payment setup has been completed successfully!',
            ]);
        }
    }
    }else{
        $seller_payment = new SellerPaymentSetting;
        $seller_payment->seller_id = !empty($seller_id) ? $seller_id : '';
        $seller_payment->razorpay_key = isset($inputs['razorpay_key']) ? $inputs['razorpay_key'] : '';
        $seller_payment->razorpay_secret = isset($inputs['razorpay_secret']) ? $inputs['razorpay_secret'] : '';
        $seller_payment->razorpay_status = isset($inputs['razorpay_payment_status']) ? $inputs['razorpay_payment_status'] : '';
        
        if($seller_payment->save()){
            return response()->json([
                'status'  => 'success',
                'message' => 'Your Payment setup has been completed successfully!',
            ]);
        }
    }  
}

// reg_3/stripe POST
public function postStripeSetup(Request $request){
    $inputs = $request->all();        
    $user = User::findOrFail($request->user_id);      
    $seller_id = $user->seller->id;
    $shop_data = Shop::where('user_id', $user->id)->first();
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
            return response()->json([
                'status'  => 'success',
                'message' => 'Your Payment setup has been completed successfully!',
            ]);
        }
    }
    }else{
        $seller_payment = new SellerPaymentSetting;
        $seller_payment->seller_id = !empty($seller_id) ? $seller_id : '';
        $seller_payment->stripe_key = isset($inputs['stripe_key']) ? $inputs['stripe_key'] : '';
        $seller_payment->stripe_secret = isset($inputs['stripe_secret']) ? $inputs['stripe_secret'] : '';
        $seller_payment->stripe_status = isset($inputs['stripe_payment_status']) ? $inputs['stripe_payment_status'] : '';
        
        if($seller_payment->save()){
            return response()->json([
                'status'  => 'success',
                'message' => 'Your Payment setup has been completed successfully!',
            ]);
        }
    }  
}

// reg_3/instamojo POST
public function postInstamojoSetup(Request $request){
    $inputs = $request->all();    
    $user = User::findOrFail($request->user_id);      
    $seller_id = $user->seller->id;
    $shop_data = Shop::where('user_id', $user->id)->first();
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
            return response()->json([
                'status'  => 'success',
                'message' => 'Your Payment setup has been completed successfully!',
            ]);
        }
    }
    }else{
        $seller_payment = new SellerPaymentSetting;
        $seller_payment->seller_id = !empty($seller_id) ? $seller_id : '';
        $seller_payment->instamojo_key = isset($inputs['instamojo_key']) ? $inputs['instamojo_key'] : '';
        $seller_payment->instamojo_token = isset($inputs['instamojo_token']) ? $inputs['instamojo_token'] : '';
        $seller_payment->instamojo_status = isset($inputs['instamojo_payment_status']) ? $inputs['instamojo_payment_status'] : '';
        
        if($seller_payment->save()){
            return response()->json([
                'status'  => 'success',
                'message' => 'Your Payment setup has been completed successfully!',
            ]);
        }
    }  
} // reg_4/shipping POST
public function postShippingSetup(Request $request) {    
    //retriving the currency price from session 
    $currency = Currency::where('code', 'Rupee')->first();
    $exchange_rate = $currency->exchange_rate;    
    $response['status'] = 0;
    $response['status'] = 'Something went wrong!';
    $inputs = $request->all();
    $user = User::findOrFail($request->user_id);      
    $seller_id = $user->seller->id;
    $shop = $user->shop;   
    if($shop->seller_type == 'services'){
        $shop->step_4 == 'complete';
        $shop->save();             
        return response()->json([
            'status'  => 4,
            'message' => 'All entries filled already',
        ]);
    }
    if($shop->seller_type == 'goods' || $shop->seller_type == 'both'){
        $inputs = $request->all();
        $shipping = \App\ShippingSetup::where('seller_id',$seller_id)->get();
        
        $shipping = \App\ShippingSetup::where('seller_id', $user->seller->id)->get();   
        $array = [];
        foreach($shipping as $ship){
            array_push($array, $ship->shipping_type);
        }  
        if(sizeof($array) == 4){ 
            $shop->step_4 = 'complete';
            $shop->save();             
            return response()->json([
                'status'  => 4,
                'message' => 'All entries filled already',
            ]);
        }
    }
    if(!empty($inputs)){
        if($inputs['tab'] == NULL){ 
            $response['status'] = 0;
            $response['message'] = 'Please Select Shipping Type';
            return response()->json($response);
        }
        if($inputs['rate_100'] == NULL){                
            $response['status'] = 0;
            $response['message'] = 'Please enter shipping price for 0-100gm';
            return response()->json($response);
        }
        if($inputs['rate_500'] == NULL){                
            $response['status'] = 0;
            $response['message'] = 'Please enter shipping price for 101-500gm';
            return response()->json($response);
        }
        if($inputs['rate_1000'] == NULL){                
            $response['status'] = 0;
            $response['message'] = 'Please enter shipping price for 501-1000gm';
            return response()->json($response);
        }
        if($inputs['rate_1500'] == NULL){                
            $response['status'] = 0;
            $response['message'] = 'Please enter shipping price for 1001-1500gm';
            return response()->json($response);
        }
        if($inputs['rate_2000'] == NULL){                
            $response['status'] = 0;
            $response['message'] = 'Please enter shipping price for 1501-2000gm';
            return response()->json($response);
        }
        if($inputs['rate_2500'] == NULL){                
            $response['status'] = 0;
            $response['message'] = 'Please enter shipping price for 2001-2500gm';
            return response()->json($response);
        }
        if($inputs['rate_3000'] == NULL){                
            $response['status'] = 0;
            $response['message'] = 'Please enter shipping price for 2501-3000gm';
            return response()->json($response);
        }
        if($inputs['rate_3500'] == NULL){                
            $response['status'] = 0;
            $response['message'] = 'Please enter shipping price for 3001-3500gm';
            return response()->json($response);
        }
        if($inputs['rate_4000'] == NULL){                
            $response['status'] = 0;
            $response['message'] = 'Please enter shipping price for 3501-4000gm';
            return response()->json($response);
        }
        if($inputs['rate_4500'] == NULL){                
            $response['status'] = 0;
            $response['message'] = 'Please enter shipping price for 4001-4500gm';
            return response()->json($response);
        }
        if($inputs['rate_5000'] == NULL){                
            $response['status'] = 0;
            $response['message'] = 'Please enter shipping price for 4501-5000gm';
            return response()->json($response);
        }
        if($inputs['more_than_5000'] == NULL){                
            $response['status'] = 0;
            $response['message'] = 'Please enter shipping price for more than 5000gm';
            return response()->json($response);
        }
        $shipping_data = ShippingSetup::where('seller_id', $seller_id)
                ->where('shipping_type', $inputs['tab'])
                ->first();
        
        if(!empty($shipping_data) && !empty($inputs['tab'])){
            if($shipping_data->shipping_type == $inputs['tab']){
                $response['status'] = 0;
                $response['message'] = 'Shipping charges for '. $inputs['tab']. ' already existed.';                    
                return response()->json($response);
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
                return response()->json([
                    'status'  => 'success',
                    'message' => 'Shipping Details Saved Successfully.',
                ]);
            }  
        }
    }            
}
// reg_5/domain POST
public function postDomainSetup(Request $request) {
    $inputs = $request->all();        
    $user = User::findOrFail($request->user_id);      
    $shop = Shop::where('user_id', $user->id)->first();
    if(!empty($shop->domain)){            
        $domain = Shop::find($shop->id);
    if(!empty($inputs) && !empty($domain->domain)){
        $domain->domain = !empty(strtolower($inputs['domain'])) ? strtolower($inputs['domain']) : '';
        if($domain->save()){
            $shop->step_5 = 'complete';
            $shop->save();
            return response()->json([
                'status'  => 'success',
                'message' => 'Your Domain setup has been completed successfully!',
            ]);
        }
    }
    }else{
        $domain = Shop::find($shop->id);
        $domain->domain = !empty(strtolower($inputs['domain'])) ? strtolower($inputs['domain']) : '';        
        if($domain->save()){
            return response()->json([
                'status'  => 'success',
                'message' => 'Your Domain setup has been completed successfully!',
            ]);
        }
    }
}
// reg_6/product POST
    public function postProduct(Request $request){

        $refund_request_addon = \App\Addon::where('unique_identifier', 'refund_request')->first();
        $user = User::findOrFail($request->user_id);
        $shops = Shop::where('user_id', $user->id)->first();
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
        if($user->user_type == 'seller'){
            $product->user_id = $user->id;
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
            return response()->json([
                'status'  => 'success',
                'message' => 'Product has been inserted successfully!',
            ]);
        }else{
            return response()->json([
                'status'  => 'failed',
                'message' => 'Something Went Wrong!',
            ]);
        }
    }
    // reg_6/services POST
    public function postServices(Request $request){
        $user = User::findOrFail($request->user_id);
        $product = new Product;
        $product->name = $request->name;
        $product->added_by = $request->added_by;
        $product->user_id = $user->id;
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
            return response()->json([
                'status'  => 'success',
                'message' => 'Product has been inserted successfully!',
            ]);
        }
        else{
            return response()->json([
                'status'  => 'failed',
                'message' => 'Something went wrong!',
            ]);
        }
    }
}
