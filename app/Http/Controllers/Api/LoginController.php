<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite; 

class LoginController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api'); // Đảm bảo người dùng đã đăng nhập
    // }

    // Đăng nhập bằng Google và tạo JWT Token
    public function loginWithGoogle(Request $request)
    {
        try {
            $googleToken = $request->input('token');    
            // Lấy thông tin người dùng từ Google
            $googleUser = Socialite::driver('google')->user();
            dd($googleUser);

            // Tìm hoặc tạo người dùng trong hệ thống
            $user = User::firstOrCreate(
                ['google_id' => $googleUser->getId()],
                [
                    'email' => $googleUser->getEmail(),
                    'full_name' => $googleUser->getName(),
                    'picture' => $googleUser->getAvatar(),
                    'role' => 0, // Role mặc định, có thể thay đổi theo yêu cầu
                ]
            );

            // Tạo JWT token cho người dùng
            $token = JWTAuth::fromUser($user);

            // Trả về thông tin người dùng cùng với token
            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'data' => [
                    'name' => $user->full_name, 
                    'email' => $user->email,
                    'google_id' => $user->google_id,
                    'token' => $token,
                ],
            ], 200);
        } catch (\Exception $e) {
            // Nếu có lỗi trong quá trình xác thực
            return response()->json(['error' => 'Unable to login with Google', 'message' => $e->getMessage()], 400);
        }
    }
}
