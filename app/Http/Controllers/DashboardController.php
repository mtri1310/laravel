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
    public function index()
    {
        try {
            // Thiết lập ngôn ngữ cho Carbon là tiếng Anh
            Carbon::setLocale('en');

            $currentDate = Carbon::today();
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
                    $query->where('payment_status', '=', 1); 
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
            $paymentsPending = Payment::where('payment_status', 2)
                ->whereDate('created_at', $currentDate)
                ->count();

            // Log các chỉ số tuần hiện tại
            Log::info('Các chỉ số Dashboard cho Tuần: ' . $latestWeek, [
                'total_amount' => $latestTotalAmount,
                'seats_booked' => $seatsBooked,
                'users_registered' => $usersRegistered,
                'payments_pending' => $paymentsPending,
            ]);

            // ================================
            // Lấy tổng doanh thu theo tháng
            // ================================
            $totalAmountPerMonth = DB::table('invoices')
                ->join('payments', 'invoices.payment_id', '=', 'payments.id') // Join bảng payments để lấy payment_status
                ->select(
                    DB::raw('YEAR(invoices.created_at) as year'),
                    DB::raw('MONTH(invoices.created_at) as month'),
                    DB::raw('SUM(invoices.total_amount) as total_amount')
                )
                ->where('payments.payment_status', 1) // Giả sử '1' tương ứng với 'Completed'
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get()
                ->map(function($item) {
                    $month_name = Carbon::create()->month($item->month)->isoFormat('MMMM'); // Tên tháng bằng tiếng Anh
                    return (object)[
                        'year' => $item->year,
                        'month' => $item->month,
                        'month_name' => $month_name,
                        'total_amount' => $item->total_amount,
                    ];
                });

            // Ghi log tổng doanh thu theo tháng
            Log::info('Total Amount Per Month:', $totalAmountPerMonth->toArray());

            // ===============================
            // Lấy số ghế đã đặt theo tháng
            // ===============================
            $seatsBookedPerMonth = DB::table('invoices')
                ->join('payments', 'invoices.payment_id', '=', 'payments.id')
                ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->join('booking_seat', 'bookings.id', '=', 'booking_seat.booking_id')
                ->select(
                    DB::raw('YEAR(invoices.created_at) as year'),
                    DB::raw('MONTH(invoices.created_at) as month'),
                    DB::raw('COUNT(booking_seat.seat_id) as seats_booked')
                )
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get()
                ->map(function($item) {
                    $month_name = Carbon::create()->month($item->month)->isoFormat('MMMM'); // Tên tháng bằng tiếng Anh
                    return (object)[
                        'year' => $item->year,
                        'month' => $item->month,
                        'month_name' => $month_name,
                        'seats_booked' => $item->seats_booked,
                    ];
                });

            // Ghi log số ghế đã đặt theo tháng
            Log::info('Seats Booked Per Month:', $seatsBookedPerMonth->toArray());
            
            // ===============================
            // Lấy số lượng payment_method trạng thái Completed theo tháng
            // ===============================
            $completedPaymentsPerMonth = DB::table('invoices')
                ->join('payments', 'invoices.payment_id', '=', 'payments.id')
                ->select(
                    DB::raw('YEAR(invoices.created_at) as year'),
                    DB::raw('MONTH(invoices.created_at) as month'),
                    DB::raw('COUNT(payments.id) as completed_count')
                )
                ->where('payments.payment_status', 1) 
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get()
                ->map(function ($item) {
                    $month_name = Carbon::create()->month($item->month)->isoFormat('MMMM');
                    return (object)[
                        'year' => $item->year,
                        'month' => $item->month,
                        'month_name' => $month_name,
                        'completed_count' => $item->completed_count,
                    ];
                });

            Log::info('Payment Completed Per Month:', $completedPaymentsPerMonth->toArray());

            // ====================================
            // Chuẩn bị dữ liệu để truyền vào view
            // ====================================
            $data = [
                'user' => auth()->user(),
                'latestTotalAmount' => $latestTotalAmount,
                'seatsBooked' => $seatsBooked,
                'usersRegistered' => $usersRegistered,
                'paymentsPending' => $paymentsPending,
                'totalAmountPerMonth' => $totalAmountPerMonth,
                'seatsBookedPerMonth' => $seatsBookedPerMonth,
                'completedPaymentsPerMonth' => $completedPaymentsPerMonth,
            ];

            return view('admin', $data);
        } catch (\Exception $e) {
            // Ghi log lỗi nếu có
            Log::error('Error fetching statistics:', ['error' => $e->getMessage()]);

            // Chuyển hướng trở lại với thông báo lỗi
            return redirect()->back()->with('messageError', 'Đã xảy ra lỗi khi lấy dữ liệu thống kê.');
        }
    }
}
