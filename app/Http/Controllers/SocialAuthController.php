<?php

namespace App\Http\Controllers;

use Socialite;
use Illuminate\Http\Request;
use App\Services\SocialAuthService;

class SocialAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function callback(SocialAuthService $service)
    {
    	$user = $service->createOrGetUser(Socialite::driver('facebook')->user());
    	auth()->login($user);

        return redirect()->to('/home');
    }

    public function redirectGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle(SocialAuthService $service)
    {
    	$user = $service->createOrGetUser(Socialite::driver('google')->user());
    	auth()->login($user);

        return redirect()->to('/home');
    }

    public function redirectLinkedin()
    {
        return Socialite::driver('linkedin')->redirect();
    }

    public function callbackLinkedin(SocialAuthService $service)
    {
    	$user = $service->createOrGetUser(Socialite::driver('linkedin')->user());
    	auth()->login($user);

        return redirect()->to('/home');
    }
}