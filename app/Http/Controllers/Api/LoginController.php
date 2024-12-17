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
