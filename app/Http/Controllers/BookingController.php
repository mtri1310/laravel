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
    public function index() : View 
    {
        $bookings = Booking::with([
            'showtime.film',
            'showtime.room',
            'user',
            'seats' // Tải sẵn quan hệ seats
        ])->latest()->paginate(10); // Điều chỉnh số lượng bản ghi phân trang nếu cần

        return view('bookings.index', compact('bookings'));
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
