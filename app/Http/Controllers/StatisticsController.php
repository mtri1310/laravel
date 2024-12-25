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
            $totalAmountPerWeek = DB::table('invoices')
                ->select(
                    DB::raw('YEARWEEK(created_at, 1) as week'),
                    DB::raw('SUM(total_amount) as total_amount')
                )
                ->groupBy('week')
                ->orderBy('week', 'asc')
                ->get()
                ->map(function($item) {
                    $year = floor($item->week / 100);
                    $week_num = $item->week % 100;
                    $date = Carbon::now()->setISODate($year, $week_num)->startOfWeek();
                    $month = $date->month;
                    $month_name = $date->isoFormat('MMMM'); // Tên tháng bằng tiếng Anh
                    return (object)[
                        'week' => $item->week,
                        'total_amount' => $item->total_amount,
                        'year' => $year,
                        'week_num' => $week_num,
                        'month' => $month,
                        'month_name' => $month_name,
                        'week_start_date' => $date->toDateString(),
                    ];
                });

            // Ghi log tổng doanh thu theo tuần
            Log::info('Total Amount Per Week:', $totalAmountPerWeek->toArray());

            // ===============================
            // Lấy số ghế đã đặt theo tuần
            // ===============================
            $seatsBookedPerWeek = DB::table('invoices')
                ->join('payments', 'invoices.payment_id', '=', 'payments.id')
                ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->join('booking_seat', 'bookings.id', '=', 'booking_seat.booking_id')
                ->select(
                    DB::raw('YEARWEEK(invoices.created_at, 1) as week'),
                    DB::raw('COUNT(booking_seat.seat_id) as seats_booked')
                )
                ->groupBy('week')
                ->orderBy('week', 'asc')
                ->get()
                ->map(function($item) {
                    $year = floor($item->week / 100);
                    $week_num = $item->week % 100;
                    $date = Carbon::now()->setISODate($year, $week_num)->startOfWeek();
                    $month = $date->month;
                    $month_name = $date->isoFormat('MMMM'); // Tên tháng bằng tiếng Anh
                    return (object)[
                        'week' => $item->week,
                        'seats_booked' => $item->seats_booked,
                        'year' => $year,
                        'week_num' => $week_num,
                        'month' => $month,
                        'month_name' => $month_name,
                        'week_start_date' => $date->toDateString(),
                    ];
                });

            // Ghi log số ghế đã đặt theo tuần
            Log::info('Seats Booked Per Week:', $seatsBookedPerWeek->toArray());

            // =========================================
            // Tổng hợp dữ liệu cho biểu đồ Donut theo tháng
            // =========================================
            $donutData = DB::table('invoices')
                ->select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('SUM(total_amount) as total_amount')
                )
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get()
                ->map(function($item) {
                    $month_name = Carbon::create()->month($item->month)->isoFormat('MMMM'); // Tên tháng bằng tiếng Anh
                    return (object)[
                        'month' => $item->month,
                        'month_name' => $month_name,
                        'total_amount' => $item->total_amount,
                    ];
                });

            // Ghi log dữ liệu biểu đồ Donut
            Log::info('Donut Chart Data:', $donutData->toArray());

            // ====================================
            // Chuẩn bị dữ liệu để truyền vào view
            // ====================================
            $data = [
                'totalAmountPerWeek' => $totalAmountPerWeek,
                'seatsBookedPerWeek' => $seatsBookedPerWeek,
                'donutData' => $donutData, // Thêm donutData vào mảng dữ liệu
            ];

            return view('statistics.index', $data);
        } catch (\Exception $e) {
            // Ghi log lỗi nếu có
            Log::error('Error fetching statistics:', ['error' => $e->getMessage()]);

            // Chuyển hướng trở lại với thông báo lỗi
            return redirect()->back()->with('messageError', 'Đã xảy ra lỗi khi lấy dữ liệu thống kê.');
        }
    }
}
