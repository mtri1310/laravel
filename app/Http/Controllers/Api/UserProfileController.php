<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    /**
     * Lấy thông tin người dùng đã đăng nhập.
     *
     * @return JsonResponse
     */
    public function getUserProfile(): JsonResponse
    {
        // Lấy người dùng đã xác thực
        $user = Auth::user();

        // Kiểm tra xem người dùng đã xác thực hay chưa
        if (!$user) {
            return response()->json([
                "status" => "error",
                "message" => "Unauthenticated"
            ], 401);
        }

     
        $userData = [
            "user_id"  => $user->id,
            "username" => $user->username,
            "email"    => $user->email,
            "phone"    => $user->phone,
            "picture"    => $user->picture,
        ];

        
        return response()->json([
            "status"  => "success",
            "message" => "User Profile",
            "data"    => [
                "user" => $userData
            ]
        ]);
    }
}
