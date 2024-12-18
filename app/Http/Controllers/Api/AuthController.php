<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite; 
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Đăng nhập bằng email và password
     */
    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember_me' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Đăng nhập không thành công.',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->input('remember_me', false);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Thông tin đăng nhập không chính xác.'
            ], 401);
        }

        $user = Auth::user();

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công.',
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60, // Thời gian hết hạn tính bằng giây
            'user' => $user
        ]);
    }

    /**
     * Đăng nhập hoặc đăng ký bằng Google
     */
    public function loginOrRegisterWithGoogle(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'token' => ['required', 'string'],
            'remember_me' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Đăng nhập không thành công.',
                'errors' => $validator->errors()
            ], 422);
        }

        $idToken = $request->input('token');
        $remember = $request->input('remember_me', false);

        try {
            // Sử dụng Socialite để lấy thông tin người dùng từ token
            $googleUser = Socialite::driver('google')->stateless()->userFromToken($idToken);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token Google không hợp lệ hoặc đã hết hạn.'
            ], 401);
        }

        // Tìm người dùng trong cơ sở dữ liệu bằng google_id hoặc email
        $user = User::where('google_id', $googleUser->getId())
                    ->orWhere('email', $googleUser->getEmail())
                    ->first();

        if (!$user) {
            // Nếu người dùng chưa tồn tại, tạo mới
            $user = User::create([
                'username' => Str::slug($googleUser->getName() . '_' . Str::random(5), '_'),
                'email' => $googleUser->getEmail(),
                'full_name' => $googleUser->getName(),
                'password' => Hash::make(Str::random(16)), // Tạo mật khẩu ngẫu nhiên
                'phone' => '',
                'picture' => $googleUser->getAvatar(),
                'role' => false,
                'google_id' => $googleUser->getId(),
            ]);
        } else {
            // Nếu người dùng đã tồn tại nhưng chưa có google_id, cập nhật
            if (!$user->google_id) {
                $user->google_id = $googleUser->getId();
                $user->save();
            }
        }

        // Tạo token JWT
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công.',
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60, // Thời gian hết hạn tính bằng giây
            'user' => $user
        ]);
    }

    /**
     * Đăng xuất người dùng
     */
    public function logout(Request $request)
    {
        auth()->logout();

        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công.'
        ]);
    }

    /**
     * Lấy thông tin người dùng hiện tại
     */
    public function getUser()
    {
        try {
            $user = auth()->user();

            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy thông tin người dùng.'
            ], 500);
        }
    }
}
