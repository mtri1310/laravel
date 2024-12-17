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
        $seats = [
            'seat' => []
        ];

        $seatStatuses = ['Selected', 'Reserved','Available'];
        $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J']; // Các hàng từ A đến J
        $maxSeatsPerRow = 10; // Số ghế tối đa mỗi hàng

        for ($i = 0; $i < 50; $i++) {
            $row = $rows[array_rand($rows)]; // Chọn ngẫu nhiên một hàng
            $number = rand(1, $maxSeatsPerRow); // Chọn ngẫu nhiên số ghế trong hàng

            $seats['seat'][] = [
                'seat_id' =>  rand(00000, 99999), // Bạn có thể thay đổi hoặc tạo động theo nhu cầu
                'seat_number' => $row . $number,
                'seat_status' => $seatStatuses[array_rand($seatStatuses)],
            ];
        }
        $seatData = [
            "status" => "success",
            "message" => "Select seat",
            "data" => [
                "seat" => $seats['seat'],
                "col" => 8,
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

