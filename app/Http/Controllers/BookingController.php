<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\BookingSeat;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Hiển thị danh sách các đơn đặt vé.
     */
    public function index(Request $request): View 
    {
        $keyword = $request->input('keyword');
        $status = $request->input('status');

        $status = intval($status);

        $orderStatusList = [
            ['id' => Booking::STATUS_PENDING, 'name' => 'Pending'],
            ['id' => Booking::STATUS_CONFIRMED, 'name' => 'Confirmed'],
            ['id' => Booking::STATUS_FAILED, 'name' => 'Failed'],
            ['id' => Booking::STATUS_CANCELLED, 'name' => 'Cancelled'],
        ];

        $bookings = Booking::with([
                'showtime.film',
                'showtime.room',
                'user',
                'seats'
            ])
            ->when($keyword, function($query, $keyword) {
                return $query->where(function($q) use ($keyword) {
                    $q->where('bookings.id', 'like', "%{$keyword}%")
                    ->orWhereHas('showtime.film', function($q2) use ($keyword) {
                        $q2->where('film_name', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('showtime.room', function($q2) use ($keyword) {
                        $q2->where('room_name', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('user', function($q2) use ($keyword) {
                        $q2->where('full_name', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('seats', function($q2) use ($keyword) {
                        $q2->where('seat_number', 'like', "%{$keyword}%");
                    });
                });
            })
            ->when($status != 0, function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy('bookings.created_at', 'desc') 
            ->orderBy('bookings.id', 'desc')
            ->paginate(10)
            ->appends(['keyword' => $keyword, 'status' => $status]);


        return view('bookings.index', compact('bookings', 'keyword', 'status', 'orderStatusList'));
    }

    /**
     * Hiển thị form tạo mới đơn đặt vé.
     */
    public function create() : View
    {
        return view('bookings.create');
    }

    /**
     * Lưu đơn đặt vé mới.
     */
    public function store(BookingRequest $request) : RedirectResponse
    {
        try {
            $data = $request->validated();

            // Bắt đầu transaction để đảm bảo tính nhất quán dữ liệu
            DB::beginTransaction();

            // Tạo đơn đặt vé với status 'pending'
            $booking = Booking::create([
                'showtime_id' => $data['showtime_id'],
                'user_id' => $data['user_id'],
                'status' => 'pending', // Thiết lập trạng thái đơn đặt vé
                'created_at' => now(),
                // Thêm các trường khác nếu có
            ]);

            // Tạo các bản ghi trong bảng booking_seat
            foreach ($data['seats'] as $seat_id) {
                BookingSeat::create([
                    'booking_id' => $booking->id,
                    'seat_id' => $seat_id,
                ]);
            }

            // Commit transaction
            DB::commit();

            return redirect()->route('bookings.index')
                ->with('messageSuccess', 'New booking has been added successfully.');
        } catch (\Exception $e) {
            // Rollback transaction nếu có lỗi xảy ra
            DB::rollback();
            Log::error('Booking Store Failed: ' . $e->getMessage());
            return back()->with('messageError', 'An unexpected error occurred while adding the booking.');
        }
    }

    /**
     * Xử lý hủy đơn đặt vé.
     */
    public function destroy($booking_id): RedirectResponse
    {
        try {
            $booking = Booking::findOrFail($booking_id);

            if ($booking->status !== Booking::STATUS_PENDING) {
                return back()->with('messageError', 'Only pending bookings can be cancelled.');
            }

            // Bắt đầu transaction
            DB::beginTransaction();

            // Cập nhật trạng thái đơn đặt vé thành 'cancelled'
            $booking->status = Booking::STATUS_CANCELLED;
            $booking->save();

            // // Xóa các bản ghi trong booking_seat để giải phóng ghế
            // BookingSeat::where('booking_id', $booking->id)->delete();

            // Commit transaction
            DB::commit();

            return redirect()->route('bookings.index')
                ->with('messageSuccess', 'Booking has been cancelled successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Booking Cancel Failed: ' . $e->getMessage());
            return back()->with('messageError', 'An unexpected error occurred while cancelling the booking.');
        }
    }
}
