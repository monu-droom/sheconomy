<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Auth;
use DB;
use Hash;
use App\Blog;
use App\Press;
use App\Attribute;
use App\Category;
use App\FlashDeal;
use App\Brand;
use App\SubCategory;
use App\SubSubCategory;
use App\Product;
use App\ProductStock;
use App\PickupPoint;
use App\CustomerPackage;
use App\CustomerProduct;
use App\User;
use App\Country;
use App\Seller;
use App\SellerKyc;
use App\SellerPaymentSetting;
use App\AccountType;
use App\Domain;
use App\SellerAccountTypeMapping;
use App\Shop;
use App\ContactUs;
use App\Color;
use App\Order;
use App\ShippingSetup;
use App\CountryCode;
use App\BusinessSetting;
use App\Http\Controllers\SearchController;
use ImageOptimizer;
use Cookie;
use Illuminate\Support\Str;
use App\Mail\SecondEmailVerifyMailManager;
use App\Mail\SendContactUsManager;
use App\Models\ProductStock as ModelsProductStock;
use Mail;
use URL;
use Carbon\Carbon;
use Stevebauman\Location\Facades\Location;
use Illuminate\Support\Facades\Log as Log;

class HomeController extends Controller
{
    public function login()
    {   
        //getting the last page url to redirect after the User Login
        Session::put('intended_url',URL::previous());   

        if(Auth::check()){
            return redirect()->route('home');
        }
        return view('frontend.user_login');
    }

    public function registration(Request $request)
    {
        if(Auth::check()){
            return redirect()->route('home');
        }
        if($request->has('referral_code')){
            Cookie::queue('referral_code', $request->referral_code, 43200);
        }
        return view('frontend.user_registration');
    }

    // public function user_login(Request $request)
    // {
    //     $user = User::whereIn('user_type', ['customer', 'seller'])->where('email', $request->email)->first();
    //     if($user != null){
    //         if(Hash::check($request->password, $user->password)){
    //             if($request->has('remember')){
    //                 auth()->login($user, true);
    //             }
    //             else{
    //                 auth()->login($user, false);
    //             }
    //             return redirect()->route('dashboard');
    //         }
    //     }
    //     return back();
    // }

    public function cart_login(Request $request)
    {
        $user = User::whereIn('user_type', ['customer', 'seller'])->where('email', $request->email)->orWhere('phone', $request->email)->first();
        if($user != null){
            updateCartSetup();
            if(Hash::check($request->password, $user->password)){
                if($request->has('remember')){
                    auth()->login($user, true);
                }
                else{
                    auth()->login($user, false);
                }
            }
        }
        return back();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the admin dashboard to admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_dashboard()
    {
        return view('dashboard');
    }

    /**
     * Show the customer/seller dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request)
    {
        if(Auth::user()->user_type == 'seller'){
            if(Auth::user()->shop->step_1 != 'complete'){
                return redirect()->route('reg_1');
            }
            if(Auth::user()->shop->step_2 == null){
                return redirect()->route('steps.kyc');
            }
            if(Auth::user()->shop->step_3 != 'complete'){
                return redirect()->route('steps.payments');
            }
            if(Auth::user()->shop->step_4 != 'complete'){
                if(Auth::user()->shop->seller_type == 'goods' || Auth::user()->shop->seller_type == 'both'){
                    return redirect()->route('steps.shipping');                
                }
                if(Auth::user()->shop->seller_type == 'services'){
                    $shop = Auth::user()->shop;
                    $shops_save = Shop::findOrFail($shop->id);
                    $shop->step_4 = 'complete';
                    $shop->save();
                    return redirect()->route('steps.domain');
                }                    
            }
            if(Auth::user()->shop->step_5 != 'complete'){
                return redirect()->route('steps.domain');
            }

            if(Auth::user()->shop->step_6 != 'complete' && Auth::user()->shop->seller_type == 'goods'){
                return redirect()->route('steps.product');
            }
            elseif(Auth::user()->shop->step_6 != 'complete' && Auth::user()->shop->seller_type == 'services'){
                return redirect()->route('steps.service');
            }
            elseif(Auth::user()->shop->step_6 != 'complete' && Auth::user()->shop->seller_type == 'both'){
                return redirect()->route('steps.product');
            }
                        
            $countries = Country::get('name');
            $user_id = Auth::user()->id;
            return view('frontend.seller.dashboard', compact('user_id', 'countries'));
        }
        elseif(Auth::user()->user_type == 'customer'){
            $user_id = Auth::user()->id;
            $address = \App\Address::where('user_id', $user_id)->first();
            if(!empty($address) && strtolower($address->country) == 'india'){
                $request->session()->put('currency_code', 'Rupee');
            }
            return view('frontend.customer.dashboard', compact('user_id'));
        }
        else {
            abort(404);
        }
    }

    public function dashboardCountry(Request $request){
        $shops = Shop::where('user_id', Auth::id())->first();
        $shop = Shop::find($shops->id);
        if(isset($request->country)){
            $shop->country = $request->country;
            $shop->save();
            flash('Country Added Successfully')->success();
            return redirect('dashboard');
        }
    }

    public function dashboardSellin(Request $request){
        $shops = Shop::where('user_id', Auth::id())->first();
        $shop = Shop::find($shops->id);
        if(isset($request->sell_in)){
            $shop->sell_in = strtolower($request->sell_in);
            $shop->save();
            flash('Selling Region Updated Successfully')->success();
            return redirect('dashboard');
        }        
    }

    public function dashboardSellerType(Request $request){
        $shops = Shop::where('user_id', Auth::id())->first();
        $shop = Shop::find($shops->id);
        if(isset($request->seller_type)){
            $shop->seller_type = $request->seller_type;
            $shop->save();
            flash('Seller Type Added Successfully')->success();
            return redirect('dashboard');
        }
    }

    public function profile(Request $request)
    {
        $this->mail_callback($request);
        $countries = Country::get('name');
        if(Auth::user()->user_type == 'customer'){
            return view('frontend.customer.profile');
        }
        elseif(Auth::user()->user_type == 'seller'){
            $user = Auth::user();
            $seller = $user->seller;
            $kyc_status = $seller->kyc_status;
            $query = Shop::where('user_id',$user->id)->first();
            $country = strtolower($query->country);
            $country_codes = CountryCode::get();
            $account_type = AccountType::get('account_type');
            $account_type = json_decode($account_type, true);

            //if already done kyc
            $account = SellerAccountTypeMapping::where('seller_id', $seller->id)->first();
            if(!empty($account)){
                $seller_account_type = AccountType::where('id', $account->account_type_id)->first();
                $seller_kyc = SellerKyc::where('seller_id', $seller->id)->first();
                $seller_kind_1 = array('individuals', 'sole proprietors', 'freelancers', 'consultants');
                $seller_kind_2 = array('registered business/company', 'authorized reseller', 'partnership', 'trading company', 'ngo');
                if(in_array($seller_account_type->account_type, $seller_kind_1)){
                    $seller_type = 'individual';
                }
                if(in_array($seller_account_type->account_type, $seller_kind_2)){
                    $seller_type = 'pro';
                }
                if(!empty($seller_kyc) && $kyc_status == 'rejected'){
                    return view('frontend.seller.profile_kyc', compact('kyc_status','country','country_codes','account_type', 'countries', 'seller_account_type', 'seller_kyc', 'seller_type'));
                }
            }
            $user_address = \App\Address::where('user_id', Auth::id())->first();
            return view('frontend.seller.profile', compact('kyc_status','country', 'country_codes','account_type', 'countries', 'user_address'));
        }
    }

    public function kyc(Request $request)
    {
        $this->mail_callback($request);
        
        if(Auth::user()->user_type == 'customer'){
            return view('frontend.customer.profile');
        }
        elseif(Auth::user()->user_type == 'seller'){
            $user = Auth::user();
            $seller = $user->seller;
            $kyc_status = $seller->kyc_status;
            $query = Shop::where('user_id',$user->id)->first();
            $country = strtolower($query->country);
            $account_type = AccountType::get('account_type');
            $account_type = json_decode($account_type, true);
            //if already done kyc
            $account = SellerAccountTypeMapping::where('seller_id', $seller->id)->first();
            if(!empty($account)){
                $seller_account_type = AccountType::where('id', $account->account_type_id)->first();
                $seller_kyc = SellerKyc::where('seller_id', $seller->id)->first();
                $seller_kind_1 = array('individuals', 'sole proprietors', 'freelancers', 'consultants');
                $seller_kind_2 = array('registered business/company', 'authorized reseller', 'partnership', 'trading company', 'ngo');
                if(in_array($seller_account_type->account_type, $seller_kind_1)){
                    $seller_type = 'individual';
                }
                if(in_array($seller_account_type->account_type, $seller_kind_2)){
                    $seller_type = 'pro';
                }
                if(!empty($seller_kyc) && $kyc_status == 'rejected'){
                    return view('frontend.seller.reject_kyc', compact('kyc_status','country','account_type', 'seller_account_type', 'seller_kyc', 'seller_type'));
                }
            }
            return view('frontend.seller.kyc', compact('kyc_status','country','account_type'));
        }
    }

    public function getKyc(Request $request)
    {
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

    public function customer_update_profile(Request $request)
    {
        $user = Auth::user();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $user->phone = $request->phone;

        if($request->new_password != null && ($request->new_password == $request->confirm_password)){
            $user->password = Hash::make($request->new_password);
        }

        if($request->hasFile('photo')){
            $user->avatar_original = $request->photo->store('uploads/users');
        }

        if($user->save()){
            flash(__('Your Profile has been updated successfully!'))->success();
            return back();
        }

        flash(__('Sorry! Something went wrong.'))->error();
        return back();
    }


    public function seller_update_profile(Request $request)
    {
        $user = Auth::user();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->postal_code = $request->postal_code;
        $country_code = $request->country_code;
        $phone = $request->phone;
        $user->phone = $country_code .'  '. $phone;
        
        if($request->has('country')){
            $shop = Shop::where('user_id', Auth::user()->id)->first();
            $shop->country = $request->country;
            $shop->save();
        }
        if($request->new_password != null && ($request->new_password == $request->confirm_password)){
            $user->password = Hash::make($request->new_password);
        }

        if($request->hasFile('photo')){
            $user->avatar_original = $request->photo->store('uploads');
        }

        $seller = $user->seller;
        $seller->cash_on_delivery_status = $request->cash_on_delivery_status;
        $seller->bank_payment_status = $request->bank_payment_status;
        $seller->bank_name = $request->bank_name;
        $seller->bank_acc_name = $request->bank_acc_name;
        $seller->bank_acc_no = $request->bank_acc_no;
        $seller->bank_routing_no = $request->bank_routing_no;

        if($user->save() && $seller->save()){
            flash(__('Your Profile has been updated successfully!'))->success();
            return back();
        }

        flash(__('Sorry! Something went wrong.'))->error();
        return back();
    }

    /**
     * Show the application frontend home.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.index');
    }

    public function flash_deal_details($slug)
    {
        $flash_deal = FlashDeal::where('slug', $slug)->first();
        if($flash_deal != null)
            return view('frontend.flash_deal_details', compact('flash_deal'));
        else {
            abort(404);
        }
    }

    public function load_featured_section(){
        //---For GeoLocation Of A User----
        $location = session()->get('location');
        $user_country = $location->countryName;
        $lat1 = deg2rad($location->latitude);
        $lng1 = deg2rad($location->longitude);
        $lat2 = '';                            
        $lng2 = '';  
        //FOR SERVICES 
        $all_service_users = [];
        $shops_service = DB::table('users')
                    ->select('users.id as user_id', 'shops.latitude as latitude', 'shops.longitude as longitude')
                    ->join('shops', 'users.id', '=', 'shops.user_id')
                    ->join('products', 'products.user_id', '=', 'users.id')
                    ->where('products.featured', 1)
                    ->where('products.digital', 1)
                    ->where('products.published', 1)
                    ->distinct()
                    ->get();
        foreach($shops_service as $shops_serv){
            $lat_serv_2 = deg2rad($shops_serv->latitude);
            $lng_serv_2 = deg2rad($shops_serv->longitude);
            //Haversine Formula 
            $dis_long = $lng_serv_2 - $lng1; 
            $dis_lati = $lat_serv_2 - $lat1;                
            $value = pow(sin($dis_lati/2),2)+cos($lat1)*cos($lat_serv_2)*pow(sin($dis_long/2),2);                 
            $result = 2 * asin(sqrt($value));                 
            $radius = 3958.756;                 
            $dist_in_miles = $result*$radius;
            $dist_in_kms = $dist_in_miles * 1.609344;
            if($dist_in_kms <= 21){
                $all_service_users[$dist_in_kms] = $shops_serv->user_id;
            }
        }
        ksort($all_service_users);
        //FOR PRODUCTS 
        $all_prod_users = [];
        if($user_country == 'india'){
            $shops_prod = DB::table('users')
                        ->select('users.id as user_id', 'shops.latitude as latitude', 'shops.longitude as longitude')
                        ->join('shops', 'users.id', '=', 'shops.user_id')
                        ->join('products', 'products.user_id', '=', 'users.id')
                        ->where('products.featured', 1)
                        ->where('products.digital', 0)
                        ->where('products.published', 1)
                        ->where('shops.sell_in', strtolower($user_country))
                        ->distinct()
                        ->get();            
        }else{
            $shops_prod = DB::table('users')
                        ->select('users.id as user_id', 'shops.latitude as latitude', 'shops.longitude as longitude')
                        ->join('shops', 'users.id', '=', 'shops.user_id')
                        ->join('products', 'products.user_id', '=', 'users.id')
                        ->where('products.featured', 1)
                        ->where('products.digital', 0)
                        ->where('products.published', 1)
                        ->distinct()
                        ->get();            
        }
        foreach($shops_prod as $shop){
            $lat2 = deg2rad($shop->latitude);
            $lng2 = deg2rad($shop->longitude);
            //Haversine Formula 
            $dlong = $lng2 - $lng1; 
            $dlati = $lat2 - $lat1;                
            $val = pow(sin($dlati/2),2)+cos($lat1)*cos($lat2)*pow(sin($dlong/2),2);                 
            $res = 2 * asin(sqrt($val));                 
            $radius = 3958.756;                 
            $distance_in_miles = $res*$radius;
            $distance_in_kms = $distance_in_miles * 1.609344;
            $all_prod_users[$distance_in_kms] = $shop->user_id;
            if($dist_in_kms <= 21){
                $all_prod_users[$dist_in_kms] = $shop->user_id;
            }
        }
        ksort($all_prod_users);
        return view('frontend.partials.featured_products_section', compact('all_service_users', 'all_prod_users'));
    }

    public function load_best_selling_section(){
        return view('frontend.partials.best_selling_section');
    }

    public function load_home_categories_section(){
        return view('frontend.partials.home_categories_section');
    }

    public function load_best_sellers_section(){
        return view('frontend.partials.best_sellers_section');
    }

    public function trackOrder(Request $request)
    {
        if($request->has('order_code')){
            $order = Order::where('code', $request->order_code)->first();
            if($order != null){
                return view('frontend.track_order', compact('order'));
            }
        }
        return view('frontend.track_order');
    }

    public function product(Request $request, $slug)
    {
        $rate = [
            'rate_100' => '0-100gm',
            'rate_500' => '101-500gm',
            'rate_1000' => '501-1000gm',
            'rate_1500' => '1001-1500gm',
            'rate_2000' => '1501-2000gm',
            'rate_2500' => '2001-2500gm',
            'rate_3000' => '2501-3000gm',
            'rate_3500' => '3001-3500gm',
            'rate_4000' => '3501-4000gm',
            'rate_4500' => '4001-4500gm',
            'rate_5000' => '4501-5000gm'
        ];
        $detailedProduct  = Product::where('slug', $slug)->first();
        //Artificial Intelligence
        if($detailedProduct->user_id != Auth::id()){
            $is_product_factor = \App\ProductFactor::where('product_id', $detailedProduct->id)->first();
            $product_factor = '';
            if($is_product_factor){
                $product_factor = \App\ProductFactor::findOrFail($is_product_factor->id);
            }
            if($product_factor){
                $product_factor->clicks += 1;            
            }else{
                $product_factor = new \App\ProductFactor;
                $product_factor->product_id = $detailedProduct->id;
                $product_factor->rating = $detailedProduct->rating;
                $product_factor->clicks += 1;
                if($detailedProduct->variant_product == 1){
                    $stock = ProductStock::where('product_id', $detailedProduct->id)->first();
                    $product_factor->price = $stock->price;
                    if($stock->qty != 0){
                        $product_factor->availiblity = 1;
                    }else{
                        $product_factor->availiblity = 0;                        
                    }
                }else{
                    $product_factor->price = $detailedProduct->unit_price;
                    if($detailedProduct->current_stock != 0){ 
                        $product_factor->availiblity = 1;       
                    }else{
                        $product_factor->availiblity = 0;       
                    }             
                }
            }
            $product_factor->save(); 
        }       
        //reviews
        $review = \App\Review::where('product_id', $detailedProduct->id)->orderBy('id', 'DESC')->get();
        $seller = Seller::where('user_id', $detailedProduct->user_id)->first(); 
        $seller_reviews = \App\SellerRating::where('seller_id', $seller->id)->get();
        // if(in_array($detailedProduct->weight, $))    
        $product_stock = ProductStock::where('product_id', $detailedProduct->id)->first();
        $user_id = $detailedProduct->user_id;
        $product_stock = ProductStock::where('product_id', $detailedProduct->id)->first();
        //fetching data from shop table of the user whose product is listed......
        $seller_id = Seller::where('user_id', $user_id)->first('id');
        $seller_id = $seller_id->id;
        $domain = Shop::where('user_id', $user_id)->first();
        $payment_details = SellerPaymentSetting::where('seller_id', $seller_id)->first();
        $payment_enabled = 0;
        if(!isset($payment_details->payment_status)){
            $payment_enabled = 0;
        }elseif(isset($payment_details->payment_status)){
            if($payment_details->payment_status == '1' || $payment_details->payment_status == 1 || $payment_details->razorpay_status == '1' || $payment_details->razorpay_status == 1 || $payment_details->stripe_status == '1' || $payment_details->stripe_status == 1 || $payment_details->instamojo_status == '1' || $payment_details->instamojo_status == 1){
                $payment_enabled = 1;
            }
        }else{
            $payment_enabled = 0;
        }
        $shipping_details = ShippingSetup::where('seller_id', $seller_id)->get();
        $shipping_enabled = 0;
        if($shipping_details != null){
            foreach($shipping_details as $shipping_detail){
                if(isset($shipping_detail->shipping_type) == 'local' &&
                    isset($shipping_detail->shipping_type) == 'regional' &&
                    isset($shipping_detail->shipping_type) == 'national' &&
                    isset($shipping_detail->shipping_type) == 'internation'
                ){
                    $shipping_enabled = 1;
                }
            }
        }
        if($detailedProduct!=null && $detailedProduct->published){
            updateCartSetup();
            if($request->has('product_referral_code')){
                Cookie::queue('product_referral_code', $request->product_referral_code, 43200);
                Cookie::queue('referred_product_id', $detailedProduct->id, 43200);
            }
            if($detailedProduct->variant_product > 0){
                $product_stock = ProductStock::where('product_id', $detailedProduct->id)->first();
            }
            if($detailedProduct->digital == 1){
                return view('frontend.digital_product_details', compact('detailedProduct','payment_enabled', 'domain', 'product_stock', 'shipping_enabled'));
            }
            else {
                return view('frontend.product_details', compact('detailedProduct','payment_enabled', 'domain', 'product_stock','review','seller_reviews', 'shipping_enabled'));
            }
            // return view('frontend.product_details', compact('detailedProduct'));
        }
        abort(404);
    }
    public function shop($domain)
    {
    	if($domain == 'stories'){
    	    return redirect()->away('https://stories.sheconomy.in');
    	}
        $url = url()->full();
        session()->put('full_url', $url);
        $shop = Shop::where('domain', $domain)->first();
        $seller = Seller::where('user_id', $shop->user_id)->first();
        $review = \App\SellerRating::where('seller_id', $seller->id)->orderBy('id', 'DESC')->get();
        if($shop!=null){
            if ($seller->verification_status != 0){
                return view('frontend.seller_shop', compact('shop', 'review', 'seller'));
            }
            else{
                return view('frontend.seller_shop_without_verification', compact('shop', 'review',  'seller'));
            }
        }        
        abort(404);
    }

    public function shopVisit($slug)
    {
        $url = url()->full();
        session()->put('full_url', $url);
        $shop = Shop::where('name', $slug)->first();
        $seller = Seller::where('user_id', $shop->user_id)->first();
        $review = \App\SellerRating::where('seller_id', $seller->id)->orderBy('id', 'DESC')->get();
        $seller = Seller::where('user_id', $shop->user_id)->first();
        if($shop!=null){
            if ($seller->verification_status != 0){
                return view('frontend.seller_shop', compact('shop', 'review', 'seller'));
            }
            else{
                return view('frontend.seller_shop_without_verification', compact('shop', 'review', 'seller'));
            }
        }
        abort(404);
    }

    public function filter_shop($slug, $type)
    {
        $shop  = Shop::where('slug', $slug)->first();
        $seller = Seller::where('user_id', $shop->user_id)->first();
        $review = \App\SellerRating::where('seller_id', $seller->id)->orderBy('id', 'DESC')->get();
        if($shop!=null && $type != null && $seller != null){
            return view('frontend.seller_shop', compact('shop', 'type', 'seller', 'review'));
        }
        abort(404);
    }

    public function aboutUs($slug, $type, $id)
    {
        $shop  = Shop::where('slug', $slug)->first();
        $url = session()->get('full_url');
        $qr_code = \QrCode::size(150)->generate($url);
        $seller = Seller::where('user_id', $shop->user_id)->first();
        $review = \App\SellerRating::where('seller_id', $seller->id)->orderBy('id', 'DESC')->get();
        if($shop!=null && $type != null){
            return view('frontend.seller_shop_about_us', compact('shop', 'type', 'id', 'seller', 'review','qr_code'));
        }
        abort(404);
    }

    public function sellerReview($slug, $type, $id)
    {
        $shop  = Shop::where('slug', $slug)->first();
        $seller = Seller::where('user_id', $shop->user_id)->first();
        $same_user = 0;
        if($seller->user_id == Auth::id()){
            $same_user = 1;
        }
        $orders = \App\Order::where('user_id', Auth::id())->first();
        $current_user_review = \App\SellerRating::where('user_id', Auth::id())->get();
        $rated_seller = [];
        foreach($current_user_review as $current_user){
            array_push($rated_seller, $current_user->seller_id);
        }
        $review = \App\SellerRating::where('seller_id', $id)->orderBy('id', 'DESC')->get(); 
        if($orders != null){            
            return view('frontend.seller_shop_seller_review', compact('orders', 'shop', 'seller', 'id', 'review', 'current_user_review', 'same_user', 'rated_seller'));
        }else{
            $orders = '';
            return view('frontend.seller_shop_seller_review', compact('orders', 'shop', 'seller', 'id', 'review', 'current_user_review', 'same_user', 'rated_seller'));
        }
        abort(404);
    }

    public function postSellerRating(Request $request, $id){
        if(Auth::check()){
            $seller_rating = new \App\SellerRating;
            $seller_rating->seller_id = !empty($id) ? $id : '';
            $seller_rating->user_id = Auth::id();
            $seller_rating->rating_seller = !empty($request->rating_seller) ? $request->rating_seller : '';
            if($seller_rating->save()){
                flash(__('Your Response has been submitted successfully!'))->success();
                return back();
            }else{
                flash(__('Sorry! Something went wrong.'))->error();
                return back();                
            }
        }else{
            flash(__('Kindly Login Your Account For Rating The Seller!'))->warning();
            return redirect()->route('user.login');
        }
    }

    public function policies($slug, $type, $id)
    {
        $shop  = Shop::where('slug', $slug)->first();
        $seller = Seller::where('user_id', $shop->user_id)->first();
        $review = \App\SellerRating::where('seller_id', $seller->id)->orderBy('id', 'DESC')->get();
        if($shop!=null && $type != null){
            return view('frontend.seller_shop_policies', compact('shop', 'type', 'id', 'seller', 'review'));
        }
        abort(404);
    }

    public function contactUs($slug, $type)
    {
        $shop  = Shop::where('slug', $slug)->first();
        $user = User::where('id', $shop->user_id)->first();
        $seller = Seller::where('user_id', $shop->user_id)->first();
        $review = \App\SellerRating::where('seller_id', $seller->id)->orderBy('id', 'DESC')->get();
        $contact = ContactUs::where('shop_id', $shop->id)->first();
        if($shop!=null && $type != null){
            if(!empty($contact)){
            return view('frontend.seller_shop_contact_us', compact('shop', 'type', 'user', 'review', 'seller', 'contact'));
            }else{
                return view('frontend.empty_seller_shop_contact_us', compact('shop', 'type', 'user', 'review', 'seller'));
            }
        }
        abort(404);
    }

    // public function aboutUs($id){
    //     `\Log`::info($id);
    //     $shop = Shop::find($id);
    //     if(!empty($shop)){
    //         return view('frontend.seller_shop_about_us', compact('shop'));
    //     }else{
    //         flash('Upload limit has been reached. Please upgrade your package.')->warning();
    //         return back();
    //     }
    // }

    public function listing(Request $request)
    {
        // $products = filter_products(Product::orderBy('created_at', 'desc'))->paginate(12);
        // return view('frontend.product_listing', compact('products'));
        return $this->search($request);
    }

    public function all_categories(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        return view('frontend.all_category', compact('categories'));
    }
    public function all_brands(Request $request)
    {
        $categories = Category::all();
        return view('frontend.all_brand', compact('categories'));
    }

    public function show_product_upload_form(Request $request)
    {
        if(\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated){
            if(Auth::user()->seller->remaining_uploads > 0){
                $categories = Category::all();
                return view('frontend.seller.product_upload', compact('categories'));
            }
            else {
                flash('Upload limit has been reached. Please upgrade your package.')->warning();
                return back();
            }
        }
        $categories = Category::all();
        $countries = Country::get('name');
        $seller = Shop::where('user_id', Auth::user()->id)->first();
        return view('frontend.seller.product_upload', compact('categories', 'countries', 'seller'));
    }

    public function show_product_edit_form(Request $request, $id)
    {
        $categories = Category::all();
        $product = Product::find(decrypt($id));
        $product_stock = ProductStock::where('product_id', $id)->first();
        return view('frontend.seller.product_edit', compact('categories', 'product', 'product_stock'));
    }

    public function seller_product_list(Request $request)
    {
        $search = null;
        $products = Product::where('user_id', Auth::user()->id)->where('digital', 0)->orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $search = $request->search;
            $products = $products->where('name', 'like', '%'.$search.'%');
        }
        $products = $products->paginate(10);
        return view('frontend.seller.products', compact('products', 'search'));
    }

    public function ajax_search(Request $request)
    {
        $keywords = array();
        $products = Product::where('published', 1)->where('tags', 'like', '%'.$request->search.'%')->get();
        foreach ($products as $key => $product) {
            foreach (explode(',',$product->tags) as $key => $tag) {
                if(stripos($tag, $request->search) !== false){
                    if(sizeof($keywords) > 5){
                        break;
                    }
                    else{
                        if(!in_array(strtolower($tag), $keywords)){
                            array_push($keywords, strtolower($tag));
                        }
                    }
                }
            }
        }

        $products = filter_products(Product::where('published', 1)->where('name', 'like', '%'.$request->search.'%'))->get()->take(3);

        $subsubcategories = SubSubCategory::where('name', 'like', '%'.$request->search.'%')->get()->take(3);

        $shops = Shop::whereIn('user_id', verified_sellers_id())->where('name', 'like', '%'.$request->search.'%')->get()->take(3);

        if(sizeof($keywords)>0 || sizeof($subsubcategories)>0 || sizeof($products)>0 || sizeof($shops) >0){
            return view('frontend.partials.search_content', compact('products', 'subsubcategories', 'keywords', 'shops'));
        }
        return '0';
    }

    public function search(Request $request)
    {
        $query = $request->q;
        $brand_id = (Brand::where('slug', $request->brand)->first() != null) ? Brand::where('slug', $request->brand)->first()->id : null;
        $sort_by = $request->sort_by;
        $category_id = (Category::where('slug', $request->category)->first() != null) ? Category::where('slug', $request->category)->first()->id : null;
        $subcategory_id = (SubCategory::where('slug', $request->subcategory)->first() != null) ? SubCategory::where('slug', $request->subcategory)->first()->id : null;
        $subsubcategory_id = (SubSubCategory::where('slug', $request->subsubcategory)->first() != null) ? SubSubCategory::where('slug', $request->subsubcategory)->first()->id : null;
        $is_digital = 0;
        $category_check = Category::where('id', $category_id)->first();
        $subcategory_check = SubCategory::where('id', $subcategory_id)->first();
        $subsubcategory_check = SubSubCategory::where('id', $subsubcategory_id)->first();
        if(!empty($category_check) && $category_check->digital == 1){
            $is_digital = 1;
        }
        if(!empty($subcategory_check) && $subcategory_check->digital == 1){
            $is_digital = 1;
        }
        if(!empty($subsubcategory_check) && $subsubcategory_check->digital == 1){
            $is_digital = 1;
        }        
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $min_dist = $request->min_dist;
        $max_dist = $request->max_dist;
        $seller_id = $request->seller_id;
        //---For GeoLocation Of A User----
        $location = session()->get('location');
        $user_country = $location->countryName; 
        $lat1 = deg2rad($location->latitude);
        $lng1 = deg2rad($location->longitude);
        $all_users = [];
        if($user_country == 'india'){
            $shops = DB::table('users')
                        ->select('users.id as user_id', 'shops.latitude as latitude', 'shops.longitude as longitude')
                        ->join('shops', 'users.id', '=', 'shops.user_id')
                        ->join('products', 'products.user_id', '=', 'users.id')
                        ->join('product_factor as pf', 'products.id', '=', 'pf.product_id')
                        ->where('pf.availiblity', 1)        
                        ->where('shops.sell_in', strtolower($user_country))         
                        ->orderBy('pf.clicks', 'DESC')           
                        ->orderBy('pf.view_time', 'DESC')           
                        ->orderBy('pf.rating', 'DESC')           
                        ->orderBy('pf.share', 'DESC')           
                        ->orderBy('pf.price', 'ASC')
                        ->groupBy('products.id') 
                        ->distinct()
                        ->get();
        }else{
            $shops = DB::table('users')
                        ->select('users.id as user_id', 'shops.latitude as latitude', 'shops.longitude as longitude')
                        ->join('shops', 'users.id', '=', 'shops.user_id')
                        ->join('products', 'products.user_id', '=', 'users.id')
                        ->join('product_factor as pf', 'products.id', '=', 'pf.product_id')
                        ->where('pf.availiblity', 1)          
                        ->orderBy('pf.clicks', 'DESC')           
                        ->orderBy('pf.view_time', 'DESC')           
                        ->orderBy('pf.rating', 'DESC')           
                        ->orderBy('pf.share', 'DESC')           
                        ->orderBy('pf.price', 'ASC')
                        ->groupBy('products.id') 
                        ->distinct()
                        ->get();            
        }
        foreach($shops as $shops_serv){
            $lat_serv_2 = deg2rad($shops_serv->latitude);
            $lng_serv_2 = deg2rad($shops_serv->longitude);
            //Haversine Formula 
            $dis_long = $lng_serv_2 - $lng1; 
            $dis_lati = $lat_serv_2 - $lat1;                
            $value = pow(sin($dis_lati/2),2)+cos($lat1)*cos($lat_serv_2)*pow(sin($dis_long/2),2);                 
            $result = 2 * asin(sqrt($value));                 
            $radius = 3958.756;                 
            $dist_in_miles = $result*$radius;
            $dist_in_kms = $dist_in_miles * 1.609344;
            if($request->max_dist){
                if($dist_in_kms <= $request->max_dist){
                    $all_users[$dist_in_kms] = $shops_serv->user_id;
                }        
            }else{
                if($dist_in_kms <= 21){
                    $all_users[$dist_in_kms] = $shops_serv->user_id;
                }
            }
        }
        ksort($all_users);
        //-------------------        
        $conditions = ['published' => 1, 'digital' => $is_digital];

        if($brand_id != null){
            $conditions = array_merge($conditions, ['brand_id' => $brand_id]);
        }
        if($category_id != null){
            $conditions = array_merge($conditions, ['category_id' => $category_id]);
        }
        if($subcategory_id != null){
            $conditions = array_merge($conditions, ['subcategory_id' => $subcategory_id]);
        }
        if($subsubcategory_id != null){
            $conditions = array_merge($conditions, ['subsubcategory_id' => $subsubcategory_id]);
        }
        
        if($seller_id != null){
            $conditions = array_merge($conditions, ['user_id' => Seller::findOrFail($seller_id)->user->id]);
        }
        $products = Product::where($conditions)->whereIn('user_id', $all_users);
        
        if($query != null){
            $searchController = new SearchController;
            $searchController->store($request);
            $prod_ids = [];
            $prod_desc = $products->orderBy('id', 'DESC')->get(['id', 'description']);
            foreach($prod_desc as $desc){
                $descs = explode(" ",  $desc->description);
                $descs = array_map( 'strtolower', $descs );
                if(in_array(strtolower($query), $descs)){
                    array_push($prod_ids, $desc->id);
                }
            }
            $prod_meta_title = $products->orderBy('id', 'DESC')->get(['id', 'meta_title']);
            foreach($prod_meta_title as $meta_title){
                $meta_titles = explode(" ",  $meta_title->meta_title);
                $meta_titles = array_map( 'strtolower', $meta_titles );
                if(in_array(strtolower($query), $meta_titles)){
                    array_push($prod_ids, $meta_title->id);
                }
            }
            $prod_meta_description = $products->orderBy('id', 'DESC')->get(['id', 'meta_description']);
            foreach($prod_meta_description as $meta_description){
                $meta_descriptions = explode(" ",  $meta_description->meta_description);
                $meta_descriptions = array_map( 'strtolower', $meta_descriptions );
                if(in_array(strtolower($query), $meta_descriptions)){
                    array_push($prod_ids, $meta_description->id);
                }
            }
            $prod_ids = array_unique($prod_ids);
            $products = $products->where('name', 'like', '%'.$query.'%')->orWhere('tags', 'like', '%'.$query.'%');
        }

        if($sort_by != null){
            switch ($sort_by) {
                case '1':
                    $products->orderBy('created_at', 'desc');
                    break;
                case '2':
                    $products->orderBy('created_at', 'asc');
                    break;
                case '3':
                    $products->orderBy('unit_price', 'asc');
                    break;
                case '4':
                    $products->orderBy('unit_price', 'desc');
                    break;
                default:
                    // code...
                    break;
            }
        }
        if($min_price != null && $max_price != null){
            $products = $products->where('unit_price', '>=', ($min_price / 73))->where('unit_price', '<=', ($max_price / 73));
        }

        $non_paginate_products = filter_products($products)->get();

        //Attribute Filter
        $attributes = array();
        foreach ($non_paginate_products as $key => $product) {
            if($product->attributes != null && is_array(json_decode($product->attributes))){
                foreach (json_decode($product->attributes) as $key => $value) {
                    $flag = false;
                    $pos = 0;
                    foreach ($attributes as $key => $attribute) {
                        if($attribute['id'] == $value){
                            $flag = true;
                            $pos = $key;
                            break;
                        }
                    }
                    if(!$flag){
                        $item['id'] = $value;
                        $item['values'] = array();
                        foreach (json_decode($product->choice_options) as $key => $choice_option) {
                            if($choice_option->attribute_id == $value){
                                $item['values'] = $choice_option->values;
                                break;
                            }
                        }
                        array_push($attributes, $item);
                    }
                    else {
                        foreach (json_decode($product->choice_options) as $key => $choice_option) {
                            if($choice_option->attribute_id == $value){
                                foreach ($choice_option->values as $key => $value) {
                                    if(!in_array($value, $attributes[$pos]['values'])){
                                        array_push($attributes[$pos]['values'], $value);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $selected_attributes = array();

        foreach ($attributes as $key => $attribute) {
            if($request->has('attribute_'.$attribute['id'])){
                foreach ($request['attribute_'.$attribute['id']] as $key => $value) {
                    $str = '"'.$value.'"';
                    $products = $products->where('choice_options', 'like', '%'.$str.'%');
                }

                $item['id'] = $attribute['id'];
                $item['values'] = $request['attribute_'.$attribute['id']];
                array_push($selected_attributes, $item);
            }
        }


        //Color Filter
        $all_colors = array();

        foreach ($non_paginate_products as $key => $product) {
            if ($product->colors != null) {
                foreach (json_decode($product->colors) as $key => $color) {
                    if(!in_array($color, $all_colors)){
                        array_push($all_colors, $color);
                    }
                }
            }
        }

        $selected_color = null;

        if($request->has('color')){
            $str = '"'.$request->color.'"';
            $products = $products->where('colors', 'like', '%'.$str.'%');
            $selected_color = $request->color;
        }

        $products = filter_products($products)->paginate(12)->appends(request()->query());

        return view('frontend.product_listing', compact('products', 'query', 'category_id', 'subcategory_id', 'subsubcategory_id', 'brand_id', 'sort_by', 'seller_id','min_price', 'max_price', 'attributes', 'selected_attributes', 'all_colors', 'selected_color', 'max_dist', 'min_dist'));
    }

    public function product_content(Request $request){
        $connector  = $request->connector;
        $selector   = $request->selector;
        $select     = $request->select;
        $type       = $request->type;
        productDescCache($connector,$selector,$select,$type);
    }

    public function home_settings(Request $request)
    {
        return view('home_settings.index');
    }

    public function press(Request $request)
    {
        $press = Press::get();
        return view('home_settings.press', compact('press'));
    }

    public function savePress(Request $request)
    {
        $press = new Press;
        if($request->hasFile('image')){
            $images = $request->image->getClientOriginalName();
            $request->image->storeAs('uploads',$images);
            $press->image = $images;
        }
        $press->name = isset($request->name) ? $request->name : '';
        $press->description = isset($request->description) ? $request->description : '';
        if($press->save()){
            flash('Entry has been saved successfully!')->success();
            return back();            
        }
    }
    public function blog(Request $request)
    {
        $blog = Blog::get();
        return view('home_settings.blog', compact('blog'));
    }

    public function saveBlog(Request $request)
    {
        $blog = new Blog;
        if($request->hasFile('image')){
            $images = $request->image->getClientOriginalName();
            $request->image->storeAs('uploads',$images);
            $blog->image = $images;
        }
        $blog->name = isset($request->name) ? $request->name : '';
        $blog->description = isset($request->description) ? $request->description : '';
        if($blog->save()){
            flash('Entry has been saved successfully!')->success();
            return back();            
        }                
    }

    public function getPress()
    {
        $press = Press::get();
        return view("frontend.partials.press_footer", compact('press'));
    }
    public function getBlog()
    {
        $blog = Blog::get();
        return view("frontend.partials.blog_footer", compact('blog'));        
    }

    public function top_10_settings(Request $request)
    {
        foreach (Category::all() as $key => $category) {
            if(in_array($category->id, $request->top_categories)){
                $category->top = 1;
                $category->save();
            }
            else{
                $category->top = 0;
                $category->save();
            }
        }

        foreach (Brand::all() as $key => $brand) {
            if(in_array($brand->id, $request->top_brands)){
                $brand->top = 1;
                $brand->save();
            }
            else{
                $brand->top = 0;
                $brand->save();
            }
        }

        flash(__('Top 10 categories and brands have been updated successfully'))->success();
        return redirect()->route('home_settings.index');
    }

    public function variant_price(Request $request)
    {
        $product = Product::find($request->id);
        if(!empty($product)){
            $product_stock = ProductStock::where('product_id', $product->id)->first();
        }
        $variant_img = '';
        if($product_stock == ''){
            $code = session()->get('currency_code');
            if(strtolower($code) == 'rupee'){
                $price = $product->unit_price;
            }else{
                $price = $product->price_usd;            
            }
            $quantity = $product->current_stock; 
            if($product->discount != null && $product->discount_type != 'percent'){
                $discounted_price = ($price - $product->discount) * $request->quantity;    
            }elseif($product->discount != null && $product->discount_type == 'percent'){
                $discounted_price -= ($price*$product->discount)/100;
            }else{
                $discounted_price = $price * $request->quantity;    
            }
            return array(
                        'price' => single_price($discounted_price), 
                        'quantity' => $quantity, 
                        'digital' => $product->digital, 
                        'variant_img' => $variant_img,
                        'id' => $product->id
                    );       
        }
        $str = '';
        $quantity = 0;
        $variant_img = $product_stock->variant_img;

        if($request->has('color')){
            $data['color'] = $request['color'];
            $str = Color::where('code', $request['color'])->first()->name;
        }

        if(json_decode(Product::find($request->id)->choice_options) != null){
            foreach (json_decode(Product::find($request->id)->choice_options) as $key => $choice) {
                if($str != null){
                    $str .= ''.str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                }
                else{
                    $str .= str_replace(' ', '', $request['attribute_id_'.$choice->attribute_id]);
                }
            }
        }
        //for matching the variant
        $str = str_split($str);
        sort($str);
        $str = implode($str);
        if($str != null && $product->variant_product != null){
            $product_stock = $product->stocks->where('variant', $str)->first();
            $code = session()->get('currency_code');
            if(strtolower($code) == 'rupee'){
                $price = $product_stock->price;
            }else{
                $price = $product_stock->price_usd;            
            }
            $quantity = $product_stock->qty;
            $variant_img = $product_stock->variant_img;
        }
        else{
            $code = session()->get('currency_code');
            if(strtolower($code) == 'rupee'){
                $price = $product->price;
            }else{
                $price = $product->price_usd;            
            }
            $quantity = $product->current_stock;
        }


        //discount calculation
        $flash_deals = \App\FlashDeal::where('status', 1)->get();
        $inFlashDeal = false;
        foreach ($flash_deals as $key => $flash_deal) {
            if ($flash_deal != null && $flash_deal->status == 1 && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date && \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first() != null) {
                $flash_deal_product = \App\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first();
                if($flash_deal_product->discount_type == 'percent'){
                    $price -= ($price*$flash_deal_product->discount)/100;
                }
                elseif($flash_deal_product->discount_type == 'amount'){
                    $price -= $flash_deal_product->discount;
                }
                $inFlashDeal = true;
                break;
            }
        }
        if (!$inFlashDeal) {
            if($product->discount_type == 'percent'){
                $price -= ($price*$product->discount)/100;
            }
            elseif($product->discount_type == 'amount_inr'){
                $price -= $product->discount;
            }else{
                $price -= $product->discount;
            }
        }
        // if($product->tax_type == 'percent'){
        //     $price += ($price*$product->tax)/100;
        // }
        // elseif($product->tax_type == 'amount'){
        //     $price += $product->tax;
        // }
        return array('price' => single_price($price*$request->quantity), 'quantity' => $quantity, 'digital' => $product->digital, 'variant_img' => $variant_img, 'id' => $product->id);
    }

    public function sellerpolicy(){
        return view("frontend.policies.sellerpolicy");
    }

    public function returnpolicy(){
        return view("frontend.policies.returnpolicy");
    }

    public function supportpolicy(){
        return view("frontend.policies.supportpolicy");
    }

    public function refundpolicy($id){
        $seller = Shop::where('user_id', $id)->first();
        return view("frontend.policies.refundpolicy", compact('seller'));
    }

    public function terms(){
        return view("frontend.policies.terms");
    }

    public function aboutFounder(){
        return view("frontend.partials.about-founder");
    }

    public function Contact_us(){
        return view("frontend.contact-us");
    }

    public function Send_contact_us(Request $request){
        $this->validate($request, [
            'name'     =>  'required',
            'email'  =>  'required|email',
            'phone' => 'required',
            'message' =>  'required'
           ]);
      
            $data = array(
                'name'      =>  $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'message'   =>   $request->message
            );
      
           Mail::to('support@sheconomy.in')->send(new SendContactUsManager($data));
           flash(__('Thank you for contacting us!'))->success();
            return back();
          }

    public function seller_terms(){
        return view("frontend.seller.seller_terms");
    }

    public function prohibited_list(){
        return view("frontend.seller.prohibited_product_list");
    }

    public function privacypolicy(){
        return view("frontend.policies.privacypolicy");
    }

    public function get_pick_ip_points(Request $request)
    {
        $pick_up_points = PickupPoint::all();
        return view('frontend.partials.pick_up_points', compact('pick_up_points'));
    }

    public function get_category_items(Request $request){
        $category = Category::findOrFail($request->id);
        return view('frontend.partials.category_elements', compact('category'));
    }

    public function premium_package_index()
    {
        $customer_packages = CustomerPackage::all();
        return view('frontend.customer_packages_lists', compact('customer_packages'));
    }

    public function seller_digital_product_list(Request $request)
    {
        $products = Product::where('user_id', Auth::user()->id)->where('digital', 1)->orderBy('created_at', 'desc')->paginate(10);
        return view('frontend.seller.digitalproducts.products', compact('products'));
    }
    public function show_digital_product_upload_form(Request $request)
    {
        if(\App\Addon::where('unique_identifier', 'seller_subscription')->first() != null && \App\Addon::where('unique_identifier', 'seller_subscription')->first()->activated){
            if(Auth::user()->seller->remaining_digital_uploads > 0){
                $business_settings = BusinessSetting::where('type', 'digital_product_upload')->first();
                $categories = Category::where('digital', 1)->get();
                return view('frontend.seller.digitalproducts.product_upload', compact('categories'));
            }
            else {
                flash('Upload limit has been reached. Please upgrade your package.')->warning();
                return back();
            }
        }
        $business_settings = BusinessSetting::where('type', 'digital_product_upload')->first();
        $categories = Category::where('digital', 1)->get();
        return view('frontend.seller.digitalproducts.product_upload', compact('categories'));
    }

    public function show_digital_product_edit_form(Request $request, $id)
    {
        $categories = Category::where('digital', 1)->get();
        $product = Product::find(decrypt($id));
        return view('frontend.seller.digitalproducts.product_edit', compact('categories', 'product'));
    }


    // Ajax call
    public function new_verify(Request $request)
    {
        $email = $request->email;

        if(isUnique($email) == '0') {
            $response['status'] = 2;
            $response['message'] = 'Email already exists!';
            return json_encode($response);
        }

        if (strcmp($verified_email, $email) == 0) {
            $response['status'] = 2;
            $response['message'] = 'This email is already verified. Click Update!';
            return json_encode($response);
        }

        $response = $this->send_verification_mail($request, $email);
        return json_encode($response);
    }


    // Form request
    public function update_email(Request $request)
    {
        $email = $request->email;
        if(isUnique($email)) {
            $this->send_verification_mail($request, $email);
            flash('A verification mail has been sent to the mail you provided us with.')->success();
            return back();

        }

        flash('Email already exists!')->warning();
        return back();

    }

    public function send_verification_mail($request, $email)
    {
        $response['status'] = 0;
        $response['message'] = 'Unknown';

        $verification_code = Str::random(32);

        $array['subject'] = 'Email Verification';
        $array['from'] = env('MAIL_USERNAME');
        $array['content'] = 'Verify your account';
        $array['link'] = route('profile').'?new_email_verificiation_code='.$verification_code.'&email='.$email;
        $array['sender'] = Auth::user()->name;
        $array['details'] = "Email Second";

        $user = Auth::user();
        $user->new_email_verificiation_code = $verification_code;
        $user->save();

        try {
            Mail::to($email)->queue(new SecondEmailVerifyMailManager($array));

            $response['status'] = 1;
            $response['message'] = translate("Your verification mail has been Sent to your email.");

        } catch (\Exception $e) {
            // return $e->getMessage();
            $response['status'] = 0;
            $response['message'] = $e->getMessage();
        }

        return $response;
    }
    public function sellerKyc(Request $request){
        // \Log::info($request);die;
        $response['status'] = 0;
        $response['message'] = 'Something went wrong!';
        $inputs = $request->all();
        if(!isset($inputs['aadhar']) || !isset($inputs['pan'])){
            flash('Kindly Upload Required Documents!')->warning();
            return back();            
        }
        $user = Auth::user();
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
                $account_id = AccountType::where('account_type', \Arr::get($inputs,'account_type',''))->first();
                if(!empty($account_id->id) && !empty($seller->id)){
                    $id = SellerAccountTypeMapping::where('seller_id', $seller->id)->first();
                    if(!empty($id)){
                        $id = $id->id;
                        $account_type = SellerAccountTypeMapping::find($id);
                        if($account_type){
                            $account_type->account_type_id = $account_id->id;
                            $account_type->seller_id = $seller->id;  
                            $account_type->save();                      
                        }
                    }else{
                        $account_mapping = new SellerAccountTypeMapping;
                        $account_mapping->account_type_id = !empty($account_id->id) ? $account_id->id : '';
                        $account_mapping->seller_id = !empty($seller->id) ? $seller->id : '';
                        $account_mapping->save();
                    }
                }
                if($seller->save()){
                    $seller_kyc = new \App\SellerKyc;
                    $seller_kyc->seller_id = $seller->id;
                    $seller_kyc->save();
                    flash(__('Your Documents has been submitted successfully!'))->success();
                    return back(); 
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
        if(!isset($inputs['age']) || !isset($inputs['address'])){
            flash('Kindly Upload Required Documents!')->warning();
            return back();            
        }
        $user = Auth::user();
        if($user->user_type == 'seller' && $user->country != 'india'){
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
                    flash(__('Your Documents has been submitted successfully!'))->success();
                    return back(); 
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
    public function getAddSellerAccountType() {
        return view('sellers.add_seller_account_type');
    }
    public function postAddSellerAccountType(Request $request) {
        $inputs = $request->all();
        $seller_account_type = new AccountType();
        $seller_account_type->account_type = \Str::lower($inputs['seller_account_type']);
        if($seller_account_type->save()){
            flash('Seller accouont type added successfully!')->success();
            return redirect()->route('all.seller.account.type');
        }
    }
    public function getAllSellerAccountType() {
        $account_type = AccountType::get();
        return view('sellers.all_account_type',compact('account_type'));
    }   

    //Seller Payment Setup
    public function getSellerPaymentSetup() {
        if(Auth::user()->user_type == 'seller'){
            $seller_id = Auth::user()->seller->id;
            if(!empty($seller_id)){
                $payment = SellerPaymentSetting::where('seller_id', $seller_id)->first();
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
            return view('frontend.seller.payment_setup', compact('payment'));
        }
    }
    public function postSellerPaymentSetup(Request $request){   
        if(Auth::user()->user_type == 'customer'){
            return view('frontend.customer.profile');
        }
        $inputs = $request->all();
        $seller_id = Auth::user()->seller->id;
        $id = SellerPaymentSetting::where('seller_id', $seller_id)->first('id');
        if(!empty($id)){
            $id = $id->id;            
            $seller_payment = SellerPaymentSetting::find($id);
        if(!empty($inputs) && !empty($seller_payment)){
            $seller_payment->seller_id = !empty($seller_id) ? $seller_id : '';
            //For PayPal
            $seller_payment->paypal_mid = isset($inputs['mid']) ? $inputs['mid'] : '';
            $seller_payment->paypal_key = isset($inputs['key']) ? $inputs['key'] : '';
            $seller_payment->paypal_email = isset($inputs['email']) ? $inputs['email'] : '';
            if(isset($inputs['payment_status'])){
                $seller_payment->payment_status = !empty($inputs['payment_status']) ? $inputs['payment_status'] : 1;
            }else{
                $seller_payment->payment_status = '0';
            }             
            if($seller_payment->save()){
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
                return redirect()->route('get.payment.setup');
            }
        }        
    }
    public function postRazorpaySetup(Request $request){
        if(Auth::user()->user_type == 'customer'){
            return view('frontend.customer.profile');
        }
        $inputs = $request->all();                
        $seller_id = Auth::user()->seller->id;
        $id = SellerPaymentSetting::where('seller_id', $seller_id)->first('id');
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
                return redirect()->route('get.payment.setup');
            }
        }  
    }
    public function postStripeSetup(Request $request){
        if(Auth::user()->user_type == 'customer'){
            return view('frontend.customer.profile');
        }
        $inputs = $request->all();        
        $seller_id = Auth::user()->seller->id;
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
                return redirect()->route('get.payment.setup');
            }
        }  
    }
    public function postInstamojoSetup(Request $request){
        if(Auth::user()->user_type == 'customer'){
            return view('frontend.customer.profile');
        }
        $inputs = $request->all();    
        $seller_id = Auth::user()->seller->id;
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
                return redirect()->route('get.payment.setup');
            }
        }  
    }
    //Seller Domain Setup
    public function domainVerify(Request $request) {
        $inputs = $request->all();
        $domain = Shop::where('domain', strtolower($inputs['domain']))->first();
        if(!empty($domain) && $domain->domain == strtolower($inputs['domain'])){
            // username is already exist 
            echo '<div style="color: red;" id="not_available"> <b>'.$inputs['domain']. '.sheconomy.in' .'</b> is already in use! </div>';
        }else{
            // username is avaialable to use.
            echo '<div style="color: green;" id="available"> <b>'.$inputs['domain']. '.sheconomy.in' . '</b> is avaialable! </div>';
        }
    }

    public function getDomainSetup() {
        if(Auth::user()->user_type == 'seller'){
            $user = Auth::user();
            $domain = Shop::where('user_id', $user->id)->first();
            if(empty($domain->domain)){
                $domain = new Shop;
                $domain->domain = '';
            }
            return view('frontend.seller.domain_setup', compact('domain'));
        }
    }    

    public function postDomainSetup(Request $request) {
        if(Auth::user()->user_type == 'customer'){
            return view('frontend.customer.profile');
        }
        $inputs = $request->all();        
        $user = Auth::user();
        $shop = Shop::where('user_id', $user->id)->first();
        if(!empty($shop->domain)){            
            $domain = Shop::find($shop->id);
            $all_domains = Shop::where('domain', '<>', '')->get();
            $sub_domain = [];
            foreach($all_domains as $all_domain){
                $sub_domain[] = $all_domain->domain;
            }            
            if(in_array(strtolower($inputs['domain']),$sub_domain)){
                flash(__('Domain Already Existed!'))->warning();
                return back();
            }
        if(!empty($inputs) && !empty($domain->domain)){
            $domain->domain = !empty(strtolower($inputs['domain'])) ? strtolower($inputs['domain']) : '';
            if($domain->save()){
                flash(__('Your Domain setup has been updated successfully!'))->success();
                return redirect()->route('get.domain.setup');
            }
        }
        }else{
            $domain = Shop::find($shop->id);
            $domain->domain = !empty(strtolower($inputs['domain'])) ? strtolower($inputs['domain']) : '';
            
            if($domain->save()){
                flash(__('Your Domain setup has been completed successfully!'))->success();
                return redirect()->route('get.domain.setup');
            }
        }
    }

    public function hidePhone(Request $request)
    {
       $inputs = $request->all();
       $response['status'] = 0;
       $response['message'] = 'Something Went Wrong!';
       $contact_id = $request->route('id');
       $contact = ContactUs::where('id', $contact_id)->first();
       $contact = ContactUs::find($contact->id);
       if(!empty($contact) && !empty($inputs) && $inputs['selected'] == 1){           
            $contact->is_hide_phone = !empty($inputs['selected']) ? $inputs['selected'] : 0;
            if($contact->save()){
                $response['status'] = 1;
                $response['message'] = 'Your phone is now hidden to customers!';
            }
       }
       if(!empty($contact) && !empty($inputs) && $inputs['selected'] == 0){           
        $contact->is_hide_phone = $inputs['selected'];
        if($contact->save()){
            $response['status'] = 1;
            $response['message'] = 'Your phone is now visible to customers!';
        }
    }
       return json_encode($response);
    }

    public function hideAddress(Request $request)
    {
       $inputs = $request->all();
       $response['status'] = 0;
       $response['message'] = 'Something Went Wrong!';
       $contact_id = $request->route('id');
       $contact = ContactUs::where('id', $contact_id)->first();
       $contact = ContactUs::find($contact->id);
       if(!empty($contact) && !empty($inputs) && $inputs['selected'] == 1){           
            $contact->is_hide_address = !empty($inputs['selected']) ? $inputs['selected'] : 0;
            if($contact->save()){
                $response['status'] = 1;
                $response['message'] = 'Your address in now hidden to customers!';
            }
       }
       if(!empty($contact) && !empty($inputs) && $inputs['selected'] == 0){           
        $contact->is_hide_address = $inputs['selected'];
        if($contact->save()){
            $response['status'] = 1;
            $response['message'] = 'Your address is now visible to customers!';
        }
    }
       return json_encode($response);
    }

    public function getPolicy(){
        $shop = Auth::user()->shop;
        return view('frontend.seller.policies', compact('shop'));
    }

   public function addPolicy(Request $request, $id){
            $shop = Shop::find($id);
            if($shop->seller_type == 'goods' || $shop->seller_type == 'both'){
                if($request->has('refund_policy') && $request->has('shipping_policy') && $request->has('payment_policy')){
                    $shop->refund_policy = $request->refund_policy;
                    $shop->shipping_policy = $request->shipping_policy;
                    $shop->payment_policy = $request->payment_policy;
        
                    if($shop->save()){
                        flash(translate('Your Shop has been updated successfully!'))->success();
                        return back();
                    }
                    flash(translate('Sorry! Something went wrong.'))->error();
                    return back();
                }
            }else{
                if($request->has('refund_policy') && $request->has('payment_policy')){
                    $shop->refund_policy = $request->refund_policy;
                    $shop->shipping_policy = $request->shipping_policy;
                    $shop->payment_policy = $request->payment_policy;
        
                if($shop->save()){
                    flash(translate('Your Shop has been updated successfully!'))->success();
                    return back();
                }
                flash(translate('Sorry! Something went wrong.'))->error();
                return back();
            }
        }
    }

    public function variantImg(Request $request){
        $inputs = $request->all();
        $variant_images = [];
        $detailedProduct = [];
        if(sizeof($inputs) == 4){
            $rate = [
                'rate_100' => '0-100gm',
                'rate_500' => '101-500gm',
                'rate_1000' => '501-1000gm',
                'rate_1500' => '1001-1500gm',
                'rate_2000' => '1501-2000gm',
                'rate_2500' => '2001-2500gm',
                'rate_3000' => '2501-3000gm',
                'rate_3500' => '3001-3500gm',
                'rate_4000' => '3501-4000gm',
                'rate_4500' => '4001-4500gm',
                'rate_5000' => '4501-5000gm'
            ];
            $detailedProduct  = Product::where('id', $inputs['id'])->first();
            //reviews
            $review = \App\Review::where('product_id', $detailedProduct->id)->orderBy('id', 'DESC')->get();
            $seller = Seller::where('user_id', $detailedProduct->user_id)->first(); 
            $seller_reviews = \App\SellerRating::where('seller_id', $seller->id)->get();
            // if(in_array($detailedProduct->weight, $))    
            $product_stock = ProductStock::where('product_id', $detailedProduct->id)->first();
            $user_id = $detailedProduct->user_id;
            $product_stock = ProductStock::where('product_id', $detailedProduct->id)->first();
            //fetching data from shop table of the user whose product is listed......
            $seller_id = Seller::where('user_id', $user_id)->first('id');
            $seller_id = $seller_id->id;
            $domain = Shop::where('user_id', $user_id)->first();
            $payment_details = SellerPaymentSetting::where('seller_id', $seller_id)->first('payment_status');
            $payment_enabled = 0;
            if(!isset($payment_details->payment_status)){
                $payment_enabled = 0;
            }elseif(isset($payment_details->payment_status)){
                if($payment_details->payment_status == '1' || $payment_details->payment_status == 1){
                    $payment_enabled = 1;
                }
            }else{
                $payment_enabled = 0;
            }
            $shipping_details = ShippingSetup::where('seller_id', $seller_id)->get();
            $shipping_enabled = 0;
            if($shipping_details != null){
                foreach($shipping_details as $shipping_detail){
                    if(isset($shipping_detail->shipping_type) == 'local' &&
                        isset($shipping_detail->shipping_type) == 'regional' &&
                        isset($shipping_detail->shipping_type) == 'national' &&
                        isset($shipping_detail->shipping_type) == 'internation'
                    ){
                        $shipping_enabled = 1;
                    }
                }
            }
            if($detailedProduct!=null && $detailedProduct->published){
                updateCartSetup();
                if($request->has('product_referral_code')){
                    Cookie::queue('product_referral_code', $request->product_referral_code, 43200);
                    Cookie::queue('referred_product_id', $detailedProduct->id, 43200);
                }
                if($detailedProduct->variant_product > 0){
                    $product_stock = ProductStock::where('product_id', $detailedProduct->id)->first();
                }
                if($detailedProduct->digital == 1){
                    return view('frontend.digital_product_details', compact('detailedProduct','payment_enabled', 'domain', 'product_stock', 'shipping_enabled'));
                }
                else {
                    return view('frontend.product_details', compact('detailedProduct','payment_enabled', 'domain', 'product_stock','review','seller_reviews', 'shipping_enabled'));
                }
            }
        }
        $attrs = '';
        $color = Color::where('code', isset($inputs['color']) ? $inputs['color'] : '')->first();
        $color = isset($color->name) ? $color->name : '';
        $attributes = \App\Attribute::get();
        foreach($attributes as $i => $val){
            $at_key = 'attribute_id_'.$i;
            if(isset($inputs[$at_key])){
                $attribute_size = \App\Attribute::all()->count();
                $attr_array = [];
                for($j = 1; $j <= $attribute_size; $j++){
                    $key = 'attribute_id_'.$j;
                    if(isset($inputs[$key])){
                        array_push($attr_array, $inputs[$key]);
                    }
                }
                $attrs = implode('', $attr_array);
                $attrs = str_split($attrs);
                sort($attrs);
                $attrs = implode('', $attrs);
                $inputs['attrs'] = $attrs;
            }
        }
        if(isset($inputs['color']) && isset($inputs['attrs'])){
            $attrs = $attrs.$color;
            $attrs = str_split($attrs);
            sort($attrs);
            $attrs = implode($attrs);
            $inputs['attrs'] = $attrs;
        }elseif(isset($inputs['color']) && !isset($inputs['attrs'])){
            $attrs = $color;
            $attrs = str_split($attrs);
            sort($attrs);
            $attrs = implode($attrs);
            $inputs['attrs'] = $attrs;
        }
        if(!empty($inputs)){
            if(isset($inputs['attrs']) && !isset($inputs['color'])){
                $detailedProduct = Product::findOrFail($inputs['id']);
                $variant_images = ProductStock::where('product_id', isset($inputs['id']) ? $inputs['id'] : '')
                                ->where('variant', preg_replace('/\s+/', '', $inputs['attrs']))
                                ->first();
            }
        }
        if(isset($inputs['color']) && !isset($inputs['attrs'])){
            $detailedProduct = Product::findOrFail($inputs['id']);
            $variant_images = ProductStock::where('product_id', isset($inputs['id']) ? $inputs['id'] : '')
                            ->where('variant', preg_replace('/\s+/', '', $inputs['attrs']))
                            ->first();
        }
        if(isset($inputs['color']) && isset($inputs['attrs'])){
            $detailedProduct = Product::findOrFail($inputs['id']);
            $variant_images = ProductStock::where('product_id', isset($inputs['id']) ? $inputs['id'] : '')
                            ->where('variant', preg_replace('/\s+/', '', $inputs['attrs']))
                            ->first(); 
        }
        return view('frontend.partials.variant_img', compact('variant_images','detailedProduct'));
    }

    public function shopAddress(Request $request){
        $inputs = $request->all();
        if(!empty($inputs)){
            $user = Auth::user();
            $shop = Shop::find($user->shop->id);
            if(!empty($shop)){
                $shop->address = isset($inputs['address']) ? $inputs['address'] : '';
                $shop->country = isset($inputs['country']) ? $inputs['country'] : '';
                $shop->state = isset($inputs['state']) ? $inputs['state'] : '';
                $shop->city = isset($inputs['city']) ? $inputs['city'] : '';
                if($shop->save()){
                    flash(translate('Your Shop Address has been updated successfully!'))->success();
                    return back();
                }
            }
        }
    }

    public function visitingCards(){
        $shop = Auth::user()->shop;
        $seller = Seller::where('user_id', Auth::user()->id)->first();
        return view('frontend.cards.choose-card', compact('shop', 'seller'));
    }

    public function saveVisitingCards(Request $request){
        $user = Auth::user()->id;
        $seller = Seller::where('user_id', $user)->first();
        if(isset($request->image)){
            $seller->visiting_cards = $request->image;
            $seller->save();
            flash('Image Saved Successfully')->success();
            return back();
        }
    }

    public function letterHead(){
        $shop = Auth::user()->shop;
        $seller = Seller::where('user_id', Auth::user()->id)->first();
        return view('frontend.cards.choose-letter-head', compact('shop', 'seller'));
    }

    public function saveLetterHead(Request $request){
        $user = Auth::user()->id;
        $seller = Seller::where('user_id', $user)->first();
        if(isset($request->image)){
            $seller->letter_head = $request->image;
            $seller->save();
            flash('Image Saved Successfully')->success();
            return back();
        }
    }
    public function viewTime(Request $request){
        $product_info = Product::findOrFail($request->product_id);
        if($product_info->user_id != Auth::id()){
            $now = strtotime(Carbon::now());
            $start_date = strtotime($request->start_date);
            $view_time = $now - $start_date;
            //Artificial Intelligence
            $is_product_factor = \App\ProductFactor::where('product_id', $request->product_id)->first();
            $product_factor = '';
            if($is_product_factor){
                $product_factor = \App\ProductFactor::findOrFail($is_product_factor->id);
            }
            if($product_factor){
                $time_taken = (int)$product_factor->view_time + (int)$view_time;
                $product_factor->view_time = $time_taken;            
            }else{
                $product_factor = new \App\ProductFactor;
                $product_factor->product_id = $request->product_id;  
                $product_factor->view_time = $view_time;    
            }
            $product_factor->save();  
        }
    }
}

    
