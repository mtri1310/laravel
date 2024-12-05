<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SelectSeatController extends Controller
{
    public function getSelectSeat(): JsonResponse
    {
        // Dữ liệu bạn muốn trả về dưới dạng JSON
        $seatData = [
            "status" => "success",
            "message" => "Select seat",
            "data" => [
                "seat" => [
                    "seat_id" => "123",
                    "seat_number" => "H7, H8",
                    "seat_status" => "Available/ Reserved/ Selected"
                ],
                "showtime" => [
                    "showtime_id" => "54321",
                    "start_time" => "14h15",
                    "day" => "10 Dec"
                ],
                "invoice" => [
                    "total_amount" => "210.000VND"
                ]
            ]
        ];

        // Trả về JSON response
        return response()->json($seatData);
    }
}

