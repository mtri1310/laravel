<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Booking;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin', ['user' => auth()->user()]);
    }
    public function index()
    {

        $currentYear = Carbon::now()->year;
        $currentWeek = Carbon::now()->weekOfYear;

        // Chuyển đổi thành YEARWEEK định dạng
        $latestWeek = $currentYear * 100 + $currentWeek;

        Log::info('Tuần Hiện Tại:', ['latest_week' => $latestWeek]);

        // ================================================
        // Lấy các chỉ số cho Tuần Hiện Tại
        // ================================================

        // 1. Tổng Tiền (Từ bảng invoice)
        $latestTotalAmount = Invoice::whereRaw('YEARWEEK(created_at, 1) = ?', [$latestWeek])
            ->whereHas('payment', function($query){
                $query->where('payment_status', '=', '1');
            })
            ->sum('total_amount');

        // 2. Số Ghế Đã Đặt (Từ bảng booking_seat và booking)
        $seatsBooked = Booking::whereRaw('YEARWEEK(bookings.created_at, 1) = ?', [$latestWeek])
            ->join('booking_seat', 'bookings.id', '=', 'booking_seat.booking_id')
            ->count('booking_seat.seat_id');

        // 3. Số Người Dùng Đăng Ký (Từ bảng user)
        $usersRegistered = User::whereRaw('YEARWEEK(created_at, 1) = ?', [$latestWeek])
            ->count();

        // 4. Số Thanh Toán Đang Chờ (Từ bảng payment)
        $paymentsPending = Payment::where('payment_status', '2')
            ->whereRaw('YEARWEEK(created_at, 1) = ?', [$latestWeek])
            ->count();

        // Log các chỉ số
        Log::info('Các chỉ số Dashboard cho Tuần: ' . $latestWeek, [
            'total_amount' => $latestTotalAmount,
            'seats_booked' => $seatsBooked,
            'users_registered' => $usersRegistered,
            'payments_pending' => $paymentsPending,
        ]);

        // Truyền dữ liệu đến view
        return view('admin', compact(
            'latestTotalAmount',
            'seatsBooked',
            'usersRegistered',
            'paymentsPending'
        ));
    }

    /**
     * Một phương thức khác nếu cần thiết (không thay đổi)
     *
     * @return \Illuminate\View\View
     */
   
}
