<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Services\SocialAccountService;

class SocialAuthController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleProviderCallback()
    {
        // Sau khi xác thực Facebook chuyển hướng về đây cùng với một token
        // Các xử lý liên quan đến đăng nhập bằng mạng xã hội cũng đưa vào đây.
        $user = SocialAccountService::createOrGetUser(Socialite::driver('facebook')->user(), 'facebook');
        auth()->login($user);
        return redirect()->to('/home');
    }
}
