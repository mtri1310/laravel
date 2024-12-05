<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(){
        $login = [
            'status' => 'success',
            'message' => 'Login Successful',
            'data' => [
                'user' =>[
                    "user_id" => "001",
                    "username" => "The Marvels",
                    "email" => "skdfnksj@gmail.com",
                    "thumbnail" => "https://example.com/poster/the-marvels.jpg",
                    "phone" => "012155152105"
                ],
            ],
        ];
        return response()->json($login);
    }
}
