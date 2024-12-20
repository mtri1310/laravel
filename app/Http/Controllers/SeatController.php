<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Seat;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class SeatController extends Controller
{
    /**
     * Display a listing of the seats for a specific room.
     */
    public function index(Room $room): View
    {
        $now = Carbon::now();

        // Bước 1: Lấy tất cả các đặt chỗ hợp lệ (active bookings) cho phòng này
        $activeBookings = Booking::whereHas('showtime', function($q) use ($now, $room) {
            $q->where('room_id', $room->id)
              ->join('films', 'showtimes.film_id', '=', 'films.id')
              ->whereRaw("TIMESTAMP(CONCAT(showtimes.day, ' ', showtimes.start_time)) + INTERVAL films.duration MINUTE > ?", [$now]);
        })
        ->with(['seats']) // Eager load seats liên quan
        ->get();

        // Bước 2: Thu thập tất cả các seat_id đã được đặt từ các đặt chỗ hợp lệ
        $bookedSeatIds = $activeBookings->flatMap(function($booking) {
            return $booking->seats->pluck('id');
        })->unique()->toArray();

        // Bước 3: Lấy tất cả các ghế trong phòng và đánh dấu nếu ghế đã được đặt
        $seats = Seat::where('room_id', $room->id)
            ->get()
            ->map(function($seat) use ($bookedSeatIds) {
                $seat->is_booked = in_array($seat->id, $bookedSeatIds);
                return $seat;
            })
            ->groupBy(function($seat) {
                // Nhóm ghế theo hàng (ví dụ: 'A', 'B', ...)
                return strtoupper(substr($seat->seat_number, 0, 1));
            });

        return view('seats.index', compact('room', 'seats'));
    }
}
