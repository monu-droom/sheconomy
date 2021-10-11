<?php /** @noinspection PhpUndefinedClassInspection */

namespace App\Http\Controllers\Api;

use App\Models\BusinessSetting;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Session;
use App\Notifications\EmailVerificationNotification;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string|min:6'
        ]);
        $emails = User::where('email', $request->email)->first();
        if($emails){
            return response()->json([
                'message' => 'This Email is already Registered!'
            ], 200);            
        }

        $otp = rand(1000, 9999);
        Session::put('otp', $otp);
        Session::put('email', $request->email);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->otp = $otp;
        if(BusinessSetting::where('type', 'email_verification')->first()->value != 1){
            $user->email_verified_at = date('Y-m-d H:m:s');
        }
        else {
            $user->notify(new EmailVerificationNotification());
        }
        $user->save();

        $customer = new Customer;
        $customer->user_id = $user->id;
        $customer->save();
        return response()->json([
            'user_id' => $user->id,
            'message' => 'Registration Successful. Please verify and log in to your account.'
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials))
            return response()->json(['message' => 'Unauthorized'], 401);
        $user = $request->user();
        if($user->email_verified_at == null){
            return response()->json(['message' => 'Please verify your account'], 401);
        }
        $tokenResult = $user->createToken('Personal Access Token');
        return $this->loginSuccess($tokenResult, $user);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function socialLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email'
        ]);
        if (User::where('email', $request->email)->first() != null) {
            $user = User::where('email', $request->email)->first();
        } else {
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'provider_id' => $request->provider,
                'email_verified_at' => Carbon::now()
            ]);
            $user->save();
            $customer = new Customer;
            $customer->user_id = $user->id;
            $customer->save();
        }
        $tokenResult = $user->createToken('Personal Access Token');
        return $this->loginSuccess($tokenResult, $user);
    }

    protected function loginSuccess($tokenResult, $user)
    {
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(100);
        $token->save();
        $shop = \App\Shop::where('user_id', $user->id)->first();
        if($shop){
            return response()->json([
                'result' => true,
                'message' => 'Logged In Successfully',
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString(),
                'user' => [
                    'id' => $user->id,
                    'type' => $user->user_type,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                    'avatar_original' => $user->avatar_original,
                    'address' => $user->address,
                    'country'  => $user->country,
                    'city' => $user->city,
                    'postal_code' => $user->postal_code,
                    'phone' => $user->phone,
                    'step_1' => $shop->step_1,
                    'step_2' => $shop->step_2,
                    'step_3' => $shop->step_3,
                    'step_4' => $shop->step_4,
                    'step_5' => $shop->step_5,
                    'step_6' => $shop->step_6,
                ],
            ]);            
        }else{
            return response()->json([
                'result' => true,
                'message' => 'Logged In Successfully',
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString(),
                'user' => [
                    'id' => $user->id,
                    'type' => $user->user_type,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                    'avatar_original' => $user->avatar_original,
                    'address' => $user->address,
                    'country'  => $user->country,
                    'city' => $user->city,
                    'postal_code' => $user->postal_code,
                    'phone' => $user->phone
                ],
            ]);            
        }
    }
}
