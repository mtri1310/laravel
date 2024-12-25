<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
     * Yêu cầu mã xác thực để reset mật khẩu
     */
    public function requestResetCode(Request $request)
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
            // Trả về thành công để tránh lộ thông tin
            return response()->json([
                'success' => true,
                'message' => 'Nếu email này tồn tại trong hệ thống, chúng tôi đã gửi mã xác thực tới email của bạn.'
            ]);
        }

        // Tạo mã xác thực ngẫu nhiên 6 chữ số
        $code = rand(100000, 999999);

        // Lưu mã và thời gian vào bảng password_resets
        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            [
                'email' => $email,
                'code' => Hash::make($code),
                'created_at' => Carbon::now()
            ]
        );

        // Gửi mã qua email
        try {
            Mail::to($email)->send(new ResetPasswordMail($code));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể gửi email. Vui lòng thử lại sau.',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Nếu email này tồn tại trong hệ thống, chúng tôi đã gửi mã xác thực tới email của bạn.'
        ]);
    }

    /**
     * Xác nhận mã xác thực
     */
    public function verifyResetCode(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'code' => ['required', 'digits:6'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->email;
        $code = $request->code;

        // Lấy bản ghi từ bảng password_resets
        $record = DB::table('password_resets')->where('email', $email)->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy yêu cầu reset mật khẩu.'
            ], 404);
        }

        // Kiểm tra thời gian hết hạn (10 phút)
        $expiresAt = Carbon::parse($record->created_at)->addMinutes(10);
        if (Carbon::now()->greaterThan($expiresAt)) {
            return response()->json([
                'success' => false,
                'message' => 'Mã xác thực đã hết hạn.'
            ], 400);
        }

        // Kiểm tra mã
        if (!Hash::check($code, $record->code)) {
            return response()->json([
                'success' => false,
                'message' => 'Mã xác thực không hợp lệ.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Mã xác thực hợp lệ.'
        ]);
    }

    /**
     * Đổi mật khẩu mới
     */
    public function resetPassword(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'code' => ['required', 'digits:6'],
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
        $code = $request->code;
        $password = $request->password;

        // Lấy bản ghi từ bảng password_resets
        $record = DB::table('password_resets')->where('email', $email)->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy yêu cầu reset mật khẩu.'
            ], 404);
        }

        $expiresAt = Carbon::parse($record->created_at)->addMinutes(10);
        if (Carbon::now()->greaterThan($expiresAt)) {
            return response()->json([
                'success' => false,
                'message' => 'Mã xác thực đã hết hạn.'
            ], 400);
        }

        // Kiểm tra mã
        if (!Hash::check($code, $record->code)) {
            return response()->json([
                'success' => false,
                'message' => 'Mã xác thực không hợp lệ.'
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
