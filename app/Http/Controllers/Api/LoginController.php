<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite; 

class LoginController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Tìm user theo email
        $user = User::where('email', $request->email)->first();

        // Kiểm tra mật khẩu
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                "status" => "error",
                "message" => "Thông tin đăng nhập không hợp lệ"
            ], 401);
        }

        // Tạo token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            "status"  => "success",
            "message" => "Đăng nhập thành công",
            "data"    => [
                "access_token" => $token,
                "token_type"   => "Bearer",
                "user_id"  => $user->id,
                "username" => $user->username,
                "full_name" => $user->full_name,
                "email"    => $user->email,
                "phone"    => $user->phone,
                "picture"    => $user->picture,
                "role"    => $user->role,
                "google_id"    => $user->google_id,
            ]
        ]);
    }
}
