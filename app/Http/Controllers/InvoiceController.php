<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\View\View;


class InvoiceController extends Controller
{
    //
    public function index(): View
    {
        // Lấy danh sách các hóa đơn (invoice) và thông tin liên quan
        $invoices = Invoice::with('payment.booking.showtime.film', 'payment.booking.bookingSeats.seat')
                        ->paginate(10); // Loại bỏ get() và chỉ dùng paginate()

        return view('invoices.index', compact('invoices'));
    }
}
