<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\Film;


class SelectSeatController extends Controller
{

    public function getSelectSeat(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                "status" => "error",
                "message" => "Unauthenticated"
            ], 401);
        }

        $seatIds = $request->input('seat_id'); // danh sách 604,605
        $showtimeId = $request->input('showtime_id'); 
        $amount = $request->input('amount');

        if (!$seatIds || !$showtimeId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Seat IDs and Showtime ID are required.',
            ], 400);
        }

        $seatIdsArray = array_map('trim', explode(',', $seatIds));

        $showtime = Showtime::with('film')->find($showtimeId);
        if (!$showtime) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Showtime ID.',
            ], 404);
        }

        $film = $showtime->film;

        $invalidSeats = [];
        $validSeats = [];

        foreach ($seatIdsArray as $seatId) {
            $seat = Seat::find($seatId);

            // Kiểm tra ghế có tồn tại và thuộc phòng của suất chiếu không
            if (!$seat || $seat->room_id !== $showtime->room_id) {
                $invalidSeats[] = $seatId;
                continue;
            }

            // Kiểm tra ghế đã được đặt trong suất chiếu này chưa
            $existingBooking = Booking::where('showtime_id', $showtimeId)
                ->whereHas('seats', function ($query) use ($seatId) {
                    $query->where('id', $seatId);
                })
                ->exists();

            if ($existingBooking) {
                $invalidSeats[] = $seatId;
                continue;
            }

            $validSeats[] = $seat;
        }

        // Nếu có ghế không hợp lệ, trả về thông báo lỗi
        if (!empty($invalidSeats)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Some seats are invalid or already booked.',
                'invalid_seats' => $invalidSeats,
            ], 400);
        }

        $booking = Booking::create([
            'showtime_id' => $showtimeId,
            'user_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($validSeats as $seat) {
            $booking->seats()->attach($seat->id);
        }

        $seatData = array_map(function ($seat) {
            return [
                'seat_id' => $seat->id,
                'seat_number' => $seat->seat_number,
            ];
        }, $validSeats);

        return response()->json([
            'status' => 'success',
            'message' => 'Seats reserved successfully. Please complete the payment to confirm booking.',
            'data' => [
                'order_id' => $this->generateOrderID(),
                'booking_id' => $booking->id,
                'amount' => $amount,
                'film' => [
                    'id' => $film->id,
                    'film_name' => $film->film_name,
                    'thumbnail' => $film->thumbnail,
                    'duration' => $film->duration,
                    'review' => $film->review,
                    'story_line' => $film->story_line,
                    'trailer_link' => $film->link_trailer,
                    'movie_genre' => $film->movie_genre,
                    'censorship' => $film->censorship,
                    'language' => $film->language,
                    'director' => $film->director,
                    'actor' => $film->actor,
                    'status' => $film->status,
                    'release' => $film->release->format('d-m-Y'),
                ],
                'user' => [
                    'user_id' => $user->id,
                    'name' => $user->full_name,
                    'email' => $user->email,
                ],
                'seats' => $seatData,
                'showtime_id' => $showtime->id,
            ],
        ]);
    }

    private function generateOrderID()
    {
        return substr(str_shuffle(str_repeat('0123456789', 16)), 0, 16);
    }

    
}
