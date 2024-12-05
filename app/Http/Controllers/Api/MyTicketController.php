<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MyTicketController extends Controller
{
    public function getTicketDetails(): JsonResponse
    {
        // Dữ liệu mẫu bạn muốn trả về
        $ticketData = [
            "status" => "success",
            "message" => "Ticket details retrieved successfully",
            "data" => [
                "booking_id" => "123456",
                "film" => [
                    "film_name" => "Avengers: Infinity War",
                    "movie_genre" => ["Action", "Adventure", "Sci-Fi"],
                    "duration" => "2 hours 29 minutes",
                    "thumbnail" => "https://example.com/poster/avengers-endgame.jpg"
                ],
                "showtime" => [
                    "showtime_id" => "54321",
                    "start_time" => "14h15",
                    "day" => "10.12.2022"
                ],
                "seat" => [
                    "seat_number" => "H7, H8"
                ],
                "room" => [
                    "room_name" => "Section 4"
                ],
                "invoice" => [
                    "total_amount" => "210.000VND"
                ]
            ]
        ];

        // Trả về dữ liệu dưới dạng JSON
        return response()->json($ticketData);
    }
}
