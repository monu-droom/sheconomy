<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Customer;
use App\BusinessSetting;
use App\OtpConfiguration;
use App\Http\Controllers\Controller;
use App\Http\Controllers\OTPVerificationController;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Cookie;
use Nexmo;
use Twilio\Rest\Client;
use App\Notifications\EmailVerificationNotification;
use App\Mail\UserRegisterMailManager;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $customer = new Customer;
            $customer->user_id = $user->id;
            $customer->save();
        }
        else {
            if (\App\Addon::where('unique_identifier', 'otp_system')->first() != null && \App\Addon::where('unique_identifier', 'otp_system')->first()->activated){
                $user = User::create([
                    'name' => $data['name'],
                    'phone' => '+'.$data['country_code'].$data['phone'],
                    'password' => Hash::make($data['password']),
                    'verification_code' => rand(100000, 999999)
                ]);

                $customer = new Customer;
                $customer->user_id = $user->id;
                if($customer->save()){
                    flash('An email has been sent to your email id');
                    
                }

                $otpController = new OTPVerificationController;
                $otpController->send_code($user);
            }
        }

        if(Cookie::has('referral_code')){
            $referral_code = Cookie::get('referral_code');
            $referred_by_user = User::where('referral_code', $referral_code)->first();
            if($referred_by_user != null){
                $user->referred_by = $referred_by_user->id;
                $user->save();
            }
        }

        return $user;
    }

    public function register(Request $request)
    {
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            if(User::where('email', $request->email)->first() != null){
                flash(translate('Email or Phone already exists.'));
                return back();
            }
        }
        elseif (User::where('phone', '+'.$request->country_code.$request->phone)->first() != null) {
            flash(translate('Phone already exists.'));
            return back();
        }

        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        $this->guard()->login($user);

        if($user->email != null){
            if(BusinessSetting::where('type', 'email_verification')->first()->value != 1){
                $user->email_verified_at = date('Y-m-d H:m:s');
            }else{
                $user->notify(new EmailVerificationNotification());                
            }
            if($user->save()){
                event(new Registered($user));
                $inputs = $request->all();
                $username = preg_replace("/\s+/", "", $inputs['name']);
                $username = strtolower($username);
                if(!empty($inputs)){
                    $time_line = new \App\Timeline();
                    $time_line->setConnection('mysql2');
                    $time_line->username = !empty($username) ? $username.rand(1,1000) : '';
                    $time_line->name = !empty($inputs['name']) ? $inputs['name'] : '';
                    $time_line->about = '';
                    $time_line->type = 'user';
                    if($time_line->save()){
                        $inputs['password'] = Hash::make($inputs['password']);
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
                        if($com_user->save()){      
                            $user_settings = new \App\UserSettings();
                            $user_settings->setConnection('mysql2');
                            $user_settings->user_id = $com_user->id;
                            $user_settings->comment_privacy = 'everyone';
                            $user_settings->follow_privacy = 'everyone';
                            $user_settings->post_privacy = 'everyone';
                            $user_settings->confirm_follow = 'no';
                            $user_settings->timeline_post_privacy = 'everyone';
                            $user_settings->message_privacy = 'everyone';
                            $user_settings->email_follow = 'no';
                            $user_settings->email_like_post = 'no';
                            $user_settings->email_post_share = 'no';
                            $user_settings->email_comment_post = 'no';
                            $user_settings->email_like_comment = 'no';
                            $user_settings->email_reply_comment = 'no';
                            $user_settings->email_join_group = 'no';
                            $user_settings->email_like_page = 'no';
                            $user_settings->save();
                        }
                    }
                }   
                // Mail::to($user->email)->send(new UserRegisterMailManager($data));
                flash(translate('Registration successfull. Please verify your email.'))->success();
            }
        }
        
        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    protected function registered(Request $request, $user)
    {
        if ($user->email == null) {
            return redirect()->route('verification');
        }
        else {
            return redirect()->route('home');
        }
    }
}
