<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Seat;
use App\Models\Showtime;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class SeatController extends Controller
{
//     SELECT 
//     bs.seat_id AS booked_seat_id,
//     s.id AS showtime_id,
//     s.start_time,
//     s.day,
//     f.duration,
//     CONCAT(s.day, ' ', s.start_time) AS showtime_start,
//     DATE_ADD(CONCAT(s.day, ' ', s.start_time), INTERVAL f.duration MINUTE) AS showtime_end,
//     NOW() AS 'current_time'
// FROM 
//     bookings b
// INNER JOIN 
//     booking_seat bs ON b.id = bs.booking_id
// INNER JOIN 
//     showtimes s ON b.showtime_id = s.id
// INNER JOIN 
//     films f ON s.film_id = f.id
// WHERE 
//     s.room_id = 8
//     AND s.id = (
//         SELECT s2.id
//         FROM showtimes s2
//         INNER JOIN films f2 ON s2.film_id = f2.id
//         WHERE s2.room_id = 8
//           AND DATE_ADD(CONCAT(s2.day, ' ', s2.start_time), INTERVAL f2.duration MINUTE) > NOW()
//         ORDER BY s2.day ASC, s2.start_time ASC
//         LIMIT 1
//     );
    public function index(Room $room): View
    {
        $now = Carbon::now();

        // Step 1: Find the nearest upcoming showtime for the room
        $nextShowtime = Showtime::where('room_id', $room->id)
            ->whereRaw("TIMESTAMP(CONCAT(day, ' ', start_time)) > ?", [$now])
            ->orderBy('day')
            ->orderBy('start_time')
            ->first(); // Limit 1

        // If there's no upcoming showtime, handle it appropriately
        if (!$nextShowtime) {
            // Fetch all seats without booking information or handle as needed
            $seats = Seat::where('room_id', $room->id)
                ->get()
                ->map(function($seat) {
                    $seat->is_booked = false; // No bookings since no showtime
                    return $seat;
                })
                ->groupBy(function($seat) {
                    return strtoupper(substr($seat->seat_number, 0, 1));
                });

            return view('seats.index', compact('room', 'seats'))->with('message', 'No upcoming showtimes.');
        }

        // Step 2: Fetch active bookings for the nearest showtime
        $activeBookings = Booking::where('showtime_id', $nextShowtime->id)
            ->with(['seats']) // Eager load seats related to bookings
            ->get();

        // Step 3: Collect all booked seat IDs from active bookings
        $bookedSeatIds = $activeBookings->flatMap(function($booking) {
            return $booking->seats->pluck('id');
        })->unique()->toArray();

        // Step 4: Retrieve all seats in the room and mark if they are booked
        $seats = Seat::where('room_id', $room->id)
            ->get()
            ->map(function($seat) use ($bookedSeatIds) {
                $seat->is_booked = in_array($seat->id, $bookedSeatIds);
                return $seat;
            })
            ->groupBy(function($seat) {
                // Group seats by row (e.g., 'A', 'B', ...)
                return strtoupper(substr($seat->seat_number, 0, 1));
            });

        // Pass the nearest showtime to the view if needed
        return view('seats.index', compact('room', 'seats', 'nextShowtime'));
    }
}
