<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    


    public function handleGoogleCallback()
    {
       
        $googleUser = Socialite::driver('google')->user();
            // Kiểm tra nếu người dùng đã tồn tại
            $user = User::where('google_id', $googleUser->id)->orWhere('email', $googleUser->email)->first();
    
            if (!$user) {
                // Tạo user mới nếu chưa tồn tại
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => bcrypt(Str::random(16)),
                ]);
            }
    
            // Đăng nhập người dùng
            Auth::login($user, true); // `true` giúp duy trì phiên lâu dài
    
            return redirect()->route('home'); // Chuyển hướng đến trang chính
        
    }
}
