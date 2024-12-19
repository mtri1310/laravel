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
            'token_type' => 'bearer', //JWT
            'expires_in' => auth('api')->factory()->getTTL() * 60, // Thời gian hết hạn tính bằng giây
            'user' => $user
        ]);
    }
    public function register(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'max:255'], // Loại bỏ 'unique:users'
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // requires password_confirmation
            'full_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'picture' => ['nullable', 'string'], // hoặc 'image' nếu upload ảnh
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Đăng ký không thành công.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Tạo người dùng mới
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'full_name' => $request->full_name ?? '',
                'phone' => $request->phone ?? null,
                'picture' => $request->picture ?? null,
                'role' => false,
            ]);

            // Tạo token JWT
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'success' => true,
                'message' => 'Đăng ký thành công.',
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'user' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đăng ký.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request)
    {
        $user = auth()->user();

        // Validate input
        $validator = Validator::make($request->all(), [
            'username' => ['sometimes', 'string', 'max:255'], 
            'email' => ['sometimes', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
            'full_name' => ['sometimes', 'string', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:20'],
            'picture' => ['sometimes', 'nullable', 'string'], // hoặc 'image' nếu upload ảnh
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Cập nhật thông tin không thành công.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            if ($request->has('username')) {
                $user->username = $request->username;
            }
            if ($request->has('email')) {
                $user->email = $request->email;
            }
            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
            }
            if ($request->has('full_name')) {
                $user->full_name = $request->full_name;
            }
            if ($request->has('phone')) {
                $user->phone = $request->phone;
            }
            if ($request->has('picture')) {
                $user->picture = $request->picture;
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin thành công.',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật thông tin.',
                'error' => $e->getMessage()
            ], 500);
        }
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
