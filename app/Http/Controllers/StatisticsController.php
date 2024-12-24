<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Use Log facade
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index()
    {
        try {
            // Set Carbon locale to English
            Carbon::setLocale('en');

            // Retrieve total amount per week from invoices table
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
                    $month_name = $date->isoFormat('MMMM'); // Month name in English
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

            // Log total amount per week
            Log::info('Total Amount Per Week:', $totalAmountPerWeek->toArray());

            // Retrieve seats booked per week
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
                    $month_name = $date->isoFormat('MMMM'); // Month name in English
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

            // Log seats booked per week
            Log::info('Seats Booked Per Week:', $seatsBookedPerWeek->toArray());

            // Prepare data for the view
            $data = [
                'totalAmountPerWeek' => $totalAmountPerWeek,
                'seatsBookedPerWeek' => $seatsBookedPerWeek,
            ];

            return view('statistics.index', $data);
        } catch (\Exception $e) {
            // Log error if any
            Log::error('Error fetching statistics:', ['error' => $e->getMessage()]);

            // Redirect back with error message
            return redirect()->back()->with('messageError', 'An error occurred while fetching statistics.');
        }
    }
}
