<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\InvoiceRequest;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\Log;


class InvoiceController extends Controller
{
    //
    public function index(Request $request): View
    {
        $keyword = $request->input('keyword');

        // Lấy danh sách các hóa đơn (invoice) và thông tin liên quan
        $invoices = Invoice::with(['payment.booking.user', 'payment.booking.showtime.film', 'payment.booking.showtime.room', 'payment.booking.bookingSeats.seat']) // Eager load relationships
            ->when($keyword, function($query, $keyword) {
                return $query->whereHas('payment.booking.user', function($q) use ($keyword) {
                        $q->where('username', 'like', "%{$keyword}%"); // Tìm theo Username
                    })
                    ->orWhereHas('payment.booking.showtime.film', function($q) use ($keyword) {
                        $q->where('film_name', 'like', "%{$keyword}%"); // Tìm theo Film
                    })
                    ->orWhereHas('payment.booking.showtime.room', function($q) use ($keyword) {
                        $q->where('room_name', 'like', "%{$keyword}%"); // Tìm theo Room
                    })
                    ->orWhereHas('payment.booking.bookingSeats.seat', function($q) use ($keyword) {
                        $q->where('seat_number', 'like', "%{$keyword}%"); // Tìm theo Seats
                    })
                    ->orWhereHas('payment', function($q) use ($keyword) {
                        $q->where('payment_method', 'like', "%{$keyword}%") // Tìm theo Payment Method trong bảng payments
                          ->orWhere('payment_status', 'like', "%{$keyword}%") // Tìm theo Payment Status trong bảng payments
                          ->orWhere('transaction_id', 'like', "%{$keyword}%"); // Tìm theo transaction_id  trong bảng payments
                    });
            })
            ->orderBy('created_at', 'desc') // Sắp xếp theo ngày tạo giảm dần
            ->paginate(10)
            ->appends(['keyword' => $keyword]); // Giữ lại từ khóa tìm kiếm trong các liên kết phân trang

        return view('invoices.index', compact('invoices', 'keyword'));
    }

    public function create(): View
    {
        return view('invoices.create');
    }

    public function store(InvoiceRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            Invoice::create($data);

            return redirect()->route('invoices.index')
                ->with('messageSuccess', 'New invoice has been added successfully.');
        } catch (\Exception $e) {
            Log::error('Invoice Store Failed: ' . $e->getMessage());
            return back()->with('messageError', 'An unexpected error occurred while adding the invoice.');
        }
    }

}
