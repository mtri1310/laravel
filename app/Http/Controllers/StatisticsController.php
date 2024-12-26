<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Sử dụng Facade Log
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index()
    {
        try {
            // Thiết lập ngôn ngữ cho Carbon là tiếng Anh
            Carbon::setLocale('en');

            // ================================
            // Lấy tổng doanh thu theo tuần
            // ================================
            $totalAmountPerMonth = DB::table('invoices')
                ->join('payments', 'invoices.payment_id', '=', 'payments.id') // Join bảng payments để lấy payment_status
                ->select(
                    DB::raw('YEAR(invoices.created_at) as year'),
                    DB::raw('MONTH(invoices.created_at) as month'),
                    DB::raw('SUM(invoices.total_amount) as total_amount')
                )
                ->where('payments.payment_status', 'Completed') // Chỉ lấy các bản ghi có payment_status là 'Completed'
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
            // Lấy số ghế đã đặt theo thang
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
            // Lấy số lượng payment_method trạng thái Pending theo tháng
            // ===============================
            $pendingPaymentsPerMonth = DB::table('invoices')
                ->join('payments', 'invoices.payment_id', '=', 'payments.id')
                ->select(
                    DB::raw('YEAR(invoices.created_at) as year'),
                    DB::raw('MONTH(invoices.created_at) as month'),
                    DB::raw('COUNT(payments.id) as pending_count')
                )
                ->where('payments.payment_status', '=', 'Pending')
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
                        'pending_count' => $item->pending_count,
                    ];
                });

            Log::info('Pending Payments Per Month:', $pendingPaymentsPerMonth->toArray());


            // ====================================
            // Chuẩn bị dữ liệu để truyền vào view
            // ====================================
            $data = [
                'totalAmountPerMonth' => $totalAmountPerMonth,
                'seatsBookedPerMonth' => $seatsBookedPerMonth,
                'pendingPaymentsPerMonth' => $pendingPaymentsPerMonth,
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
