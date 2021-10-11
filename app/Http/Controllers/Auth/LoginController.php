<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use App\User;
use App\Customer;
use Illuminate\Http\Request;
use CoreComponentRepository;
use Illuminate\Support\Str;
use App\Shop;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    /*protected $redirectTo = '/';*/


    /**
      * Redirect the user to the Google authentication page.
      *
      * @return \Illuminate\Http\Response
      */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $request, $provider)
    {
        try {
            if($provider == 'twitter'){
                $user = Socialite::driver('twitter')->user();
            }
            else{
                $user = Socialite::driver($provider)->stateless()->user();
            }
        } catch (\Exception $e) {
            flash("Something Went wrong. Please try again.")->error();
            return redirect()->route('user.login');
        }

        // check if they're an existing user
        $existingUser = User::where('provider_id', $user->id)->orWhere('email', $user->email)->first();

        if($existingUser){
            // log them in
            auth()->login($existingUser, true);
        } else {
            // create a new user
            $newUser                  = new User;
            $newUser->name            = $user->name;
            $newUser->email           = $user->email;
            $newUser->email_verified_at = date('Y-m-d H:m:s');
            $newUser->provider_id     = $user->id;

            // $extension = pathinfo($user->avatar_original, PATHINFO_EXTENSION);
            // $filename = 'uploads/users/'.Str::random(5).'-'.$user->id.'.'.$extension;
            // $fullpath = 'public/'.$filename;
            // $file = file_get_contents($user->avatar_original);
            // file_put_contents($fullpath, $file);
            //
            // $newUser->avatar_original = $filename;
            $newUser->save();

            $customer = new Customer;
            $customer->user_id = $newUser->id;
            $customer->save();

            auth()->login($newUser, true);
        }
        if(session('link') != null){
            return redirect(session('link'));
        }
        else{
            return redirect()->route('dashboard');
        }
    }

    /**
        * Get the needed authorization credentials from the request.
        *
        * @param  \Illuminate\Http\Request  $request
        * @return array
        */
       protected function credentials(Request $request)
       {
           if(filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)){
               return $request->only($this->username(), 'password');
           }
           return ['phone'=>$request->get('email'),'password'=>$request->get('password')];
       }

    /**
     * Check user's role and redirect user based on their role
     * @return
     */
    public function authenticated()
    {
        if(auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff')
        {
            // CoreComponentRepository::instantiateShopRepository();
            return redirect()->route('admin.dashboard');
        } else {
            //creating User Account in Community
            $com_user = new \App\CommUser();
            $com_user->setConnection('mysql2');
            $is_com_user = $com_user->where('sheconomy_user_id', auth()->user()->id)->first();
            $username = preg_replace("/\s+/", "", auth()->user()->name);
            $username = strtolower($username);
            if(empty($is_com_user)){
                $time_line = new \App\Timeline();
                $time_line->setConnection('mysql2');
                $all_user = \App\Timeline::all();
                $result_array = json_decode($all_user, true);
                $username_array = [];
                foreach($result_array as $data){
                    array_push($username_array, $data['username']);
                }
                if(! in_array($username, $username_array)){
                    $time_line->username = !empty($username) ? $username : '';                        
                }else{
                    $time_line->username = !empty($username) ? $username.rand(1,1000) : '';
                }
                $time_line->name = !empty(auth()->user()->name) ? auth()->user()->name : '';
                $time_line->about = '';
                $time_line->type = 'user';
                if($time_line->save()){
                    $com_user = new \App\CommUser();  
                    $com_user->setConnection('mysql2');                
                    $com_user->timeline_id = !empty($time_line->id) ? $time_line->id : '';
                    $com_user->sheconomy_user_id = !empty(auth()->user()->id) ? auth()->user()->id : '';
                    $com_user->email = !empty(auth()->user()->email) ? auth()->user()->email : '';
                    $com_user->email_verified = 1;
                    $com_user->password = !empty(auth()->user()->password) ? auth()->user()->password : '';
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
            if(session('intended_url') != null){
                return redirect(Session::get('intended_url'));
            }
            else{
                return redirect()->route('dashboard');
            }
        }
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        flash(translate('Invalid email or password'))->error();
        return back();
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        if(auth()->user() != null && (auth()->user()->user_type == 'admin' || auth()->user()->user_type == 'staff')){
            $redirect_route = 'login';
        }
        else{
            $redirect_route = 'home';
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect()->route($redirect_route);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
