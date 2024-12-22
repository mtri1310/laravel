<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PasswordResetController extends Controller
{
    /**
     * Yêu cầu reset mật khẩu
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validate email
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->email;

        // Kiểm tra xem email có tồn tại trong hệ thống không
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'success' => true,
                'message' => 'Nếu email này tồn tại trong hệ thống, chúng tôi đã gửi liên kết reset mật khẩu tới email của bạn.'
            ]);
        }

        // Tạo token reset mật khẩu
        $token = Str::random(60);
        // $resetLink = "myapp://reset-password?token={$token}&email=" . urlencode($email);
        $resetLink = url('/reset-password?token=' . $token . '&email=' . urlencode($email));

        // Lưu token vào bảng password_resets
        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            [
                'email' => $email,
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]
        );

        // Gửi email với token reset
        try {
            Mail::to($email)->send(new ResetPasswordMail($resetLink));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể gửi email. Vui lòng thử lại sau.',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Nếu email này tồn tại trong hệ thống, chúng tôi đã gửi liên kết reset mật khẩu tới email của bạn.'
        ]);
    }

    /**
     * Reset mật khẩu mới
     */
    public function reset(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->email;
        $token = $request->token;
        $password = $request->password;

        // Lấy bản ghi từ bảng password_resets
        $record = DB::table('password_resets')->where('email', $email)->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy yêu cầu reset mật khẩu.'
            ], 404);
        }

        // Kiểm tra thời gian hết hạn (60 phút)
        $expiresAt = Carbon::parse($record->created_at)->addMinutes(60);
        if (Carbon::now()->greaterThan($expiresAt)) {
            return response()->json([
                'success' => false,
                'message' => 'Token đã hết hạn.'
            ], 400);
        }

        // Kiểm tra token
        if (!Hash::check($token, $record->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token không hợp lệ.'
            ], 400);
        }

        // Cập nhật mật khẩu cho người dùng
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Người dùng không tồn tại.'
            ], 404);
        }

        $user->password = Hash::make($password);
        $user->save();

        // Xóa bản ghi trong password_resets sau khi cập nhật mật khẩu
        DB::table('password_resets')->where('email', $email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mật khẩu đã được cập nhật thành công.'
        ]);
    }
}
