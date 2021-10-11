<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shop;
use App\User;
use App\Seller;
use App\ContactUs;
use App\BusinessSetting;
use Auth;
use Hash;
use App\Country;
use App\CountryCode;
use App\Notifications\EmailVerificationNotification;

class ShopController extends Controller
{

    public function __construct()
    {
        $this->middleware('user', ['only' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shop = Auth::user()->shop;
        return view('frontend.seller.shop', compact('shop'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::get('name');
        $country_codes = CountryCode::get();
        if(Auth::check() && Auth::user()->user_type == 'admin'){
            flash(translate('Admin can not be a seller'))->error();
            return back();
        }elseif(Auth::check() && Auth::user()->user_type == 'seller'){
            flash(translate("You're already a seller"))->error();
            return redirect('dashboard');
        }
        else{
            return view('frontend.seller_form', compact('countries', 'country_codes'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = null;
        if(!Auth::check()){
            if(User::where('email', $request->email)->first() != null){
                flash(translate('Email already exists!'))->error();
                return back();
            }
            if($request->password == $request->password_confirmation){
                $user = new User;
                $user->name = $request->name;
                $user->email = $request->email;
                $country_code = $request->country_code;
                $phone = $request->phone;
                $user->phone = $country_code .'  '. $phone;
                $user->user_type = "seller";
                $user->password = Hash::make($request->password);
                $user->save();
            }
            else{
                flash(translate('Sorry! Password did not match.'))->error();
                return back();
            }
        }
        else{
            $user = Auth::user();
            if($user->customer != null){
                $user->customer->delete();
            }
            $user->user_type = "seller";
            $user->save();
        }
       
        $seller = new Seller;
        $found_user = Seller::where('user_id',$user->id)->first();
        if($found_user){
            flash(translate('User Already Exist!'))->error();
            return back();
        }
        $seller->user_id = $user->id;
        $seller->save();
        
        if(Shop::where('user_id', $user->id)->first() == null){
            
            $shop = new Shop;
            $shop->user_id = $user->id;
            $shop->name = $request->name;
            $shop->address = $request->address;
            $shop->seller_type = $request->seller_type;
            $shop->country = $request->country;//added country
            $shop->state = $request->state;//added state
            $shop->city = $request->city;//added city
            $shop->slug = preg_replace('/\s+/', '-', $request->name).'-'.$shop->id;
            if($shop->save()){
                auth()->login($user, false);
                // Creating User on Community----
                $inputs = $request->all();
                $username = preg_replace("/\s+/", "", $inputs['name']);
                $username = strtolower($username);
                
                if(!empty($inputs)){
                    $time_line = new \App\Timeline();
                    $time_line->setConnection('mysql2');
                    $time_line->username = !empty($username) ? $username : '';
                    $time_line->name = !empty($inputs['name']) ? $inputs['name'] : '';
                    $time_line->about = '';
                    $time_line->type = '';
                    if($time_line->save()){
                        if(isset($inputs['password'])){
                            $inputs['password'] = Hash::make($inputs['password']);
                        }            
                        $com_user = new \App\CommUser();
                        $com_user->setConnection('mysql2');                
                        $com_user->timeline_id = !empty($time_line->id) ? $time_line->id : '';
                        $com_user->sheconomy_user_id = !empty($user->id) ? $user->id : '';
                        $com_user->email = !empty($inputs['email']) ? $inputs['email'] : '';
                        $com_user->email_verified = 1;
                        $com_user->password = !empty($inputs['password']) ? $inputs['password'] : '';
                        $com_user->verification_code = '';
                        $com_user->remember_token = '';
                        $com_user->active = 1;
                        $com_user->save();
                    }
                }                
                if(BusinessSetting::where('type', 'email_verification')->first()->value != 1){
                    $user->email_verified_at = date('Y-m-d H:m:s');
                    $user->save();
                }
                else {
                    $user->notify(new EmailVerificationNotification());
                }

                flash(translate('Your Shop has been created successfully!'))->success();
                // return redirect('reg_1');
            }
            else{
                $seller->delete();
                $user->user_type == 'customer';
                $user->save();
            }
        }
        
        flash(translate('Sorry! Something went wrong.'))->error();
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
        //
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
        $shop = Shop::find($id);

        if($request->has('name')){
            $shop->name = $request->name;
            if ($request->has('shipping_cost')) {
                $shop->shipping_cost = $request->shipping_cost;
            }
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

        elseif($request->has('facebook') || $request->has('google') || $request->has('twitter') || $request->has('youtube') || $request->has('instagram')){
            $shop->facebook = $request->facebook;
            $shop->google = $request->google;
            $shop->twitter = $request->twitter;
            $shop->youtube = $request->youtube;
        }
        elseif($request->has('home_text')){
            $shop->home_text = $request->home_text;
        }

        else{
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

            $shop->sliders = json_encode($sliders);
        }

        if($shop->save()){
            flash(translate('Your Shop has been updated successfully!'))->success();
            return back();
        }

        flash(translate('Sorry! Something went wrong.'))->error();
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
        //
    }

    public function verify_form(Request $request)
    {
        if(Auth::user()->seller->verification_info == null){
            $shop = Auth::user()->shop;
            return view('frontend.seller.verify_form', compact('shop'));
        }
        else {
            flash(translate('Sorry! You have sent verification request already.'))->error();
            return back();
        }
    }

    public function verify_form_store(Request $request)
    {
        $data = array();
        $i = 0;
        foreach (json_decode(BusinessSetting::where('type', 'verification_form')->first()->value) as $key => $element) {
            $item = array();
            if ($element->type == 'text') {
                $item['type'] = 'text';
                $item['label'] = $element->label;
                $item['value'] = $request['element_'.$i];
            }
            elseif ($element->type == 'select' || $element->type == 'radio') {
                $item['type'] = 'select';
                $item['label'] = $element->label;
                $item['value'] = $request['element_'.$i];
            }
            elseif ($element->type == 'multi_select') {
                $item['type'] = 'multi_select';
                $item['label'] = $element->label;
                $item['value'] = json_encode($request['element_'.$i]);
            }
            elseif ($element->type == 'file') {
                $item['type'] = 'file';
                $item['label'] = $element->label;
                $item['value'] = $request['element_'.$i]->store('uploads/verification_form');
            }
            array_push($data, $item);
            $i++;
        }
        $seller = Auth::user()->seller;
        $seller->verification_info = json_encode($data);
        if($seller->save()){
            flash(translate('Your shop verification request has been submitted successfully!'))->success();
            return redirect()->route('dashboard');
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }
     //Shop setting methods will be here!
    //Name : Monu Dahiya
    //Date : 05-11-2020
    public function getBasicInfo() {
        $shop = Auth::user()->shop;
        return view('frontend.seller.basic_info', compact('shop'));        
    }

    public function getHomeSettings() {
        $shop = Auth::user()->shop;
        return view('frontend.seller.home_settings', compact('shop'));        
    }

    public function getAboutUs() {
        $shop = Auth::user()->shop;
        return view('frontend.seller.about_us', compact('shop'));        
    }

    public function getContactUs() {
        $shop = Auth::user()->shop;
        $shop_name = $shop->name;
        $user = User::where('id', $shop->user_id)->first();
        $contact = ContactUs::where('shop_id', $shop->id)->first();
        // dd($contact->address_1);
        $seller = Seller::where('user_id', $user->id)->first();
        if(!empty($contact)){
            return view('frontend.seller.contact_us', compact('shop', 'user', 'seller', 'contact'));        
        }else{
            return view('frontend.seller.get_contact_us', compact('shop', 'user', 'seller'));     
        }
    }

    public function postContactUs(Request $request){
        $contact = new ContactUs();
        $inputs = $request->all();
        $user = Auth::user();
        $shop = Shop::where('user_id', $user->id)->first();
        if(!empty($inputs)){
            $contact->shop_id = !empty($shop->id) ? $shop->id : '';
            $contact->contact_name = !empty($inputs['contact_name']) ? $inputs['contact_name'] : '';
            $contact->company_name = !empty($inputs['company_name']) ? $inputs['company_name'] : '';
            $contact->address_1 = !empty($inputs['address_1']) ? $inputs['address_1'] : '';
            $contact->address_2 = !empty($inputs['address_2']) ? $inputs['address_2'] : '';
            $contact->address_3 = !empty($inputs['address_3']) ? $inputs['address_3'] : '';
            $contact->state = !empty($inputs['state']) ? $inputs['state'] : '';
            $contact->city = !empty($inputs['city']) ? $inputs['city'] : '';
            $contact->zip_code = !empty($inputs['zip_code']) ? $inputs['zip_code'] : '';
            $contact->country = !empty($inputs['country']) ? $inputs['country'] : '';
            $contact->email = !empty($inputs['email']) ? $inputs['email'] : '';
            $contact->phone = !empty($inputs['phone']) ? $inputs['phone'] : '';
            
            if($contact->save()){
                flash(translate('Your contact details has been saved successfully!'))->success();
            }
            return redirect()->route('shop.contact_us');
        }
    }


    public function updateContactUs(Request $request, $id){
        $contact = new ContactUs();
        $inputs = $request->all();
        $user = Auth::user();
        $shop = Shop::where('user_id', $user->id)->first();
        if(!empty($inputs)){
            $contact = ContactUs::find($id);
            $contact->shop_id = !empty($shop->id) ? $shop->id : '';
            $contact->contact_name = !empty($inputs['contact_name']) ? $inputs['contact_name'] : '';
            $contact->company_name = !empty($inputs['company_name']) ? $inputs['company_name'] : '';
            $contact->address_1 = !empty($inputs['address_1']) ? $inputs['address_1'] : '';
            $contact->address_2 = !empty($inputs['address_2']) ? $inputs['address_2'] : '';
            $contact->address_3 = !empty($inputs['address_3']) ? $inputs['address_3'] : '';
            $contact->state = !empty($inputs['state']) ? $inputs['state'] : '';
            $contact->city = !empty($inputs['city']) ? $inputs['city'] : '';
            $contact->zip_code = !empty($inputs['zip_code']) ? $inputs['zip_code'] : '';
            $contact->country = !empty($inputs['country']) ? $inputs['country'] : '';
            $contact->email = !empty($inputs['email']) ? $inputs['email'] : '';
            $contact->phone = !empty($inputs['phone']) ? $inputs['phone'] : '';
            
            if($contact->save()){
                flash(translate('Your contact details has been saved successfully!'))->success();
            }
            return redirect()->route('shop.contact_us');
        }
    }
    
    public function aboutUs(Request $request) {
        $inputs = $request->all();
        $shop = Auth::user()->shop;
        if(!empty($shop) && !empty($inputs)){
            $shop->about = !empty($inputs['about']) ? $inputs['about'] : '';
        }
        if($shop->save()){
            flash(translate('About has been saved successfully!'))->success();
        }
        return redirect()->route('get.shop.about_us');
    }

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
            return back();
        }
        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    public function seller_type(){
        $shop = Auth::user()->shop;
        $seller = Seller::where('user_id', Auth::user()->id)->first();
        return view('frontend.seller.seller_type', compact('shop','seller'));
    }

    public function update_seller_type(Request $request){
        $shop = Auth::user()->shop;
        if($request->has('seller_type')){
            $shop->seller_type = $request->seller_type;
        }
        if($shop->save()){
            flash(translate('Your Seller type has been updated successfully!'))->success();
            return back();
        }
        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }
}
