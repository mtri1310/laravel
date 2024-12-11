<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Lấy thông tin đầu vào từ yêu cầu
        $username = $request->input('username'); 
        $password = $request->input('password'); 

        // Kiểm tra điều kiện đầu vào
        if (!$username || !$password) {
            return response()->json([
                'status' => 'error',
                'message' => 'Username and password are required',
                'error_code' => 'LOGIN001',
            ], 400); // HTTP status 400 (Bad Request)
        }

        if ($username !== 'The Marvels' || $password !== 'zxczczcz') {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid username or password',
                'error_code' => 'LOGIN002',
            ], 401); // HTTP status 401 (Unauthorized)
        }


        $login = [
            'status' => 'success',
            'message' => 'Login Successful',
            'data' => [
                'user' => [
                    "user_id" => "001",
                    "username" => "The Marvels",
                    "email" => "skdfnksj@gmail.com",
                    "thumbnail" => "https://example.com/poster/the-marvels.jpg",
                    "phone" => "012155152105",
                    "password" => "zxczczcz"
                ],
            ],
        ];
        return response()->json($login);
    }
}
