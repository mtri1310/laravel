<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\View\View;

class StatisticsController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $startOfWeek = $now->startOfWeek();
        $endOfWeek = $now->endOfWeek();

        // Tổng số tiền booking theo tuần
        $totalAmountPerWeek = DB::table('payments')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->whereBetween('bookings.booking_time', [$startOfWeek, $endOfWeek])
            ->sum('payments.amount');

        // Invoice Payment Pending theo tuần
        $pendingPaymentsPerWeek = DB::table('payments')
            ->join('invoices', 'payments.id', '=', 'invoices.payment_id')
            ->whereBetween('payments.created_at', [$startOfWeek, $endOfWeek])
            ->where('payments.payment_status', 'Pending')
            ->count();

        // Số lượng ghế đã đặt theo tuần
        $seatsBookedPerWeek = DB::table('booking_seat')
            ->join('bookings', 'booking_seat.booking_id', '=', 'bookings.id')
            ->whereBetween('bookings.booking_time', [$startOfWeek, $endOfWeek])
            ->count();

        return view('statistics.index', compact('totalAmountPerWeek', 'pendingPaymentsPerWeek', 'seatsBookedPerWeek'));
    }
}
