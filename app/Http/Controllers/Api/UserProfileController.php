<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class UserProfileController extends Controller
{
    public function getUserProfile(): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                "status" => "error",
                "message" => "Unauthenticated"
            ], 401);
        }

        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                "status" => "error",
                "message" => "User profile not found"
            ], 404);
        }

        $userData = [
            "user_id" => $user->id, 
            "username" => $user->username,
            "email" => $user->email,
            "phone" => $user->phone,
        ];


        return response()->json([
            "status" => "success",
            "message" => "User Profile",
            "data" => [
                "user" => $userData
            ]
        ]);
    }
}
