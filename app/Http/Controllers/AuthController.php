<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Hiển thị trang đăng nhập
     */
    public function show()
    {
        return view("auth.login");
    }

    /**
     * Xử lý đăng nhập bằng email và password
     */
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            "email" => ['required', 'email'],
            'password' => ['required']
        ]);

        // Attempt login
        if (Auth::attempt($request->only(['email', 'password']), $request->filled('remember-me'))) {
            if (Auth::user()->role !== 1) {
                Auth::logout();
                session()->flash('message', [
                    'title' => 'Error',
                    'message' => 'You are not authorized to access this page.',
                    'type' => 'error'
                ]);
                return redirect()->route('login');
            }

            return redirect('/');
        }

        session()->flash('message', [
            'title' => 'Error',
            'message' => 'Invalid credentials',
            'type' => 'error'
        ]);
        return redirect()->back();
    }

    /**
     * Đăng xuất người dùng
     */
    public function logout()
    {
        Auth::logout();
        session()->flash('message', [
            'title' => 'Notification',
            'message' => 'You have been logged out successfully.',
            'type' => 'success'
        ]);
        return redirect()->route('login');
    }

    /**
     * Chuyển hướng người dùng đến trang đăng nhập của Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Xử lý callback sau khi người dùng đăng nhập thành công từ Google
     */
    public function handleGoogleCallback()
    {
        try {
            // Lấy thông tin người dùng từ Google
            $googleUser = Socialite::driver('google')->stateless()->user();

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
                    'password' => Hash::make('tringu123'), // Tạo mật khẩu ngẫu nhiên
                    'phone' => '',
                    'picture' => $googleUser->getAvatar(),
                    'role' => 1,
                    'google_id' => $googleUser->getId(),
                ]);
            } else {
                // Nếu người dùng đã tồn tại nhưng chưa có google_id, cập nhật
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                    $user->save();
                }
            }

            // Đăng nhập người dùng với "remember me" mặc định là true
            Auth::login($user, true);

            // Kiểm tra vai trò
            if (Auth::user()->role !== 1) {
                Auth::logout();
                session()->flash('message', [
                    'title' => 'Error',
                    'message' => 'Bạn không được phép truy cập trang này.',
                    'type' => 'error'
                ]);
                return redirect()->route('login');
            }

            return redirect()->intended('/');
        } catch (\Exception $e) {
            // Xử lý lỗi nếu có
            session()->flash('message', [
                'title' => 'Error',
                'message' => 'Đăng nhập bằng Google thất bại. Vui lòng thử lại.',
                'type' => 'error'
            ]);
            return redirect()->route('login');
        }
    }

}
