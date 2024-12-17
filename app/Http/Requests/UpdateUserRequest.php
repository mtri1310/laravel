<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|max:255|unique:users'.$this->user->id,  // Tên đăng nhập, không trùng lặp
            'password' => 'required|string|min:8',  // Mật khẩu yêu cầu tối thiểu 8 ký tự
            'email' => 'required|email|unique:users',  // Email phải hợp lệ và không trùng lặp
            'full_name' => 'required|string|max:255',  // Họ tên đầy đủ
            'phone' => 'nullable|string|max:20',  // Số điện thoại, có thể để trống
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Hình ảnh người dùng
            'remember_token' => 'nullable|string',  // Token nhớ người dùng
            'role' => 'required|in:1,0',  // Quyền người dùng
        ];
    }

}