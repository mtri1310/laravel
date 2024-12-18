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

            // Debug object $user nếu cần
            // dd($user);

            $findUser = User::where('google_id', $user->id)->first();

            if ($findUser) {
                Auth::login($findUser);
                return redirect()->intended('dashboard');
            } else {
                $newUser = User::updateOrCreate(
                    ['email' => $user->email],
                    [
                        'username' => $user->name,
                        'google_id' => $user->id,
                        'full_name' => $user->name,
                        'password' => bcrypt('123456dummy'),
                        'role'=>'1',
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
