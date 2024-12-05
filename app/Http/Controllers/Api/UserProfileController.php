<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function getUserProfile(): JsonResponse
    {
        $movieData = [
            "status" => "success",
            "message" => "User Profile",
            "data" => [
                "user" => [
                    "user_id" => "001",
                    "username" => "The Marvels",
                    "email" => "skdfnksj@gmail.com",
                    "thumbnail" => "https://example.com/poster/the-marvels.jpg",
                    "phone" => "012155152105"
                ]
            ]
        ];

        // Trả về JSON response
        return response()->json($movieData);
    }
}
