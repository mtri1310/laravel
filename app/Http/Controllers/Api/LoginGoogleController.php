<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;
class LoginGoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();

            // Tìm người dùng dựa trên google_id
            $findUser = User::where('google_id', $user->id)->first();

            if ($findUser) {
                // Đăng nhập nếu người dùng đã tồn tại
                Auth::login($findUser);
                return redirect()->intended('dashboard');
            } else {
                // Tạo người dùng mới nếu chưa tồn tại
                $newUser = User::updateOrCreate(
                    ['email' => $user->email], // Tìm người dùng dựa trên email
                    [
                        'username' => $user->name,
                        'google_id' => $user->id,
                        'full_name' => $user->name,
                        'role'=>"1",
                        'password' => bcrypt('123456dummy'), // Sử dụng bcrypt để hash mật khẩu
                    ]
                );

                Auth::login($newUser);
                return redirect()->intended('dashboard');
            }
        } catch (\Exception $e) {
            Log::error('Google Login Error: '.$e->getMessage());
            return redirect()->route('login')->with('error', 'Đăng nhập thất bại, vui lòng thử lại.');
        }
    }
}
