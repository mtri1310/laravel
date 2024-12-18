<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $userId = $this->route('user') ? $this->route('user')->id : null;

        return [
            'username' => 'required|string|max:255|unique:users' . $userId,  // Tên đăng nhập, không trùng lặp
            'password' => 'required|string|min:8',  // Mật khẩu yêu cầu tối thiểu 8 ký tự
            'email' => 'required|email|unique:users',  // Email phải hợp lệ và không trùng lặp
            'full_name' => 'required|string|max:255',  // Họ tên đầy đủ
            'phone' => 'nullable|string|max:20',  // Số điện thoại, có thể để trống
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Hình ảnh người dùng
            'remember_token' => 'nullable|string',  // Token nhớ người dùng
            'role' => 'required|in:1,0',  // Quyền người dùng
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'The username field is required.',
            'username.string' => 'The username must be a string.',
            'username.max' => 'The username may not be greater than 255 characters.',
            'username.unique' => 'The username has already been taken.',
        
            'password.required' => 'The password field is required.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 8 characters.',
        
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
        
            'full_name.required' => 'The full name field is required.',
            'full_name.string' => 'The full name must be a string.',
            'full_name.max' => 'The full name may not be greater than 255 characters.',
        
            'phone.string' => 'The phone number must be a string.',
            'phone.max' => 'The phone number may not be greater than 20 characters.',
        
            'picture.image' => 'The picture must be an image file.',
            'picture.mimes' => 'The picture must be a file of type: jpeg, png, jpg, gif, svg.',
            'picture.max' => 'The picture may not be greater than 2048 kilobytes.',
        
            'remember_token.string' => 'The remember token must be a string.',
        
            'role.required' => 'The role field is required.',
            'role.in' => 'The selected role is invalid.',
        ];
    }
}