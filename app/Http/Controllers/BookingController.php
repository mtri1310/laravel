<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Http\Requests\StoreSeatRequest;
use App\Http\Requests\UpdateSeatRequest;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    //
    public function index(Request $request): View 
    {
        $keyword = $request->input('keyword');

        $bookings = Booking::with([
                'showtime.film',
                'showtime.room',
                'user',
            ])
            ->select('bookings.*')
            ->selectSub(function($query) {
                $query->from('booking_seat')
                      ->join('seats', 'booking_seat.seat_id', '=', 'seats.id')
                      ->whereColumn('booking_seat.booking_id', 'bookings.id')
                      ->selectRaw('GROUP_CONCAT(seats.seat_number ORDER BY seats.seat_number SEPARATOR ", ")');
            }, 'seat_ids')
            ->when($keyword, function($query, $keyword) {
                return $query->where(function($q) use ($keyword) {
                    $q->where('bookings.id', 'like', "%{$keyword}%") // Tìm theo booking ID
                      ->orWhereHas('showtime.film', function($q2) use ($keyword) {
                          $q2->where('film_name', 'like', "%{$keyword}%");
                      })
                      ->orWhereHas('showtime.room', function($q2) use ($keyword) {
                          $q2->where('room_name', 'like', "%{$keyword}%");
                      })
                      ->orWhereHas('user', function($q2) use ($keyword) {
                          $q2->where('full_name', 'like', "%{$keyword}%");
                      })
                      ->orWhere('seat_ids', 'like', "%{$keyword}%"); // Tìm kiếm trong seat_ids
                });
            })
            ->orderBy('bookings.created_at', 'desc') 
            ->orderBy('bookings.id', 'desc')
            ->paginate(10)
            ->appends(['keyword' => $keyword]);

        return view('bookings.index', compact('bookings', 'keyword'));
    }

    public function create() : View
    {
        return view('bookings.create');
    }

    public function store(BookingRequest $request) : RedirectResponse
    {
        try {
            $data = $request->validated();

            Booking::create($data);

            return redirect()->route('Bookings.index')
                ->with('messageSuccess', 'New booking has been added successfully.');
        } catch (\Exception $e) {
            Log::error('Booking Store Failed: ' . $e->getMessage());
            return back()->with('messageError', 'An unexpected error occurred while adding the booking.');
        }
    }

}
