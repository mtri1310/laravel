<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\InvoiceRequest;
use App\Models\Film;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Seat;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Import DB facade

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
        return view('invoices.create', [
            'users' => User::all(),
            'films' => Film::all(),
            'rooms' => Room::all(),
            'seats' => Seat::all(),
            'payments' => Payment::all(),
            'invoice' => new Invoice(), // Truyền một invoice trống để tránh lỗi
        ]);
    }

    public function store(InvoiceRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            // Tự động tạo invoice_number nếu không có
            $data['invoice_number'] = 'INV-' . str_pad(Invoice::max('id') + 1, 6, '0', STR_PAD_LEFT);

            $data['transaction_id'] = rand(100000, 999999);
            Invoice::create($data);

            return redirect()->route('invoices.index')
                ->with('messageSuccess', 'New invoice has been added successfully.');
        } catch (\Exception $e) {
            Log::error('Invoice Store Failed: ' . $e->getMessage());
            return back()->withInput()->with('messageError', 'An unexpected error occurred while adding the invoice.');
        }
    }


    public function edit(Invoice $invoice): View
    {
        return view('invoices.create', [
            'invoice' => $invoice,
            'users' => User::all(),
            'films' => Film::all(),
            'rooms' => Room::all(),
            'seats' => Seat::all(),
            'payments' => Payment::all(),

        ]);
    }
    
    public function update(InvoiceRequest $request, Invoice $invoice): RedirectResponse
{
    try {
        $data = $request->validated();

        // Keep `invoice_number` unchanged
        $data['invoice_number'] = $invoice->invoice_number;
        

        DB::transaction(function () use ($data, $invoice) {
            Log::info('Transaction started for updating invoice', ['invoice_id' => $invoice->id]);
            Log::info('Incoming data for update', $data);

            // Update Payment
            if (isset($data['payment_id'])) {
                $payment = Payment::find($data['payment_id']);
                if ($payment) {
                    Log::info('Before payment update', [
                        'payment_id' => $payment->id,
                        'old_payment_method' => $payment->payment_method,
                        'old_payment_status' => $payment->payment_status,
                    ]);

                    $payment->payment_method = $data['payment_method'];
                    $payment->payment_status = $data['payment_status'];
                    $payment->saveQuietly(); // Ensure the update is forced

                    Log::info('After payment update', [
                        'payment_id' => $payment->id,
                        'new_payment_method' => $payment->payment_method,
                        'new_payment_status' => $payment->payment_status,
                    ]);
                } else {
                    Log::warning('Payment not found', ['payment_id' => $data['payment_id']]);
                }
            }

            // Update Invoice
            Log::info('Before invoice update', ['invoice_id' => $invoice->id, 'old_data' => $invoice->toArray()]);
            $invoice->update($data);
            Log::info('After invoice update', ['invoice_id' => $invoice->id, 'new_data' => $invoice->fresh()->toArray()]);
        });

        return redirect()->route('invoices.index')
            ->with('messageSuccess', 'Invoice has been updated successfully.');
    } catch (\Exception $e) {
        Log::error('Invoice Update Failed', [
            'error' => $e->getMessage(),
            'invoice_id' => $invoice->id,
            'data' => $request->all(),
        ]);
        return back()->withInput()->with('messageError', 'An unexpected error occurred while updating the invoice.');
    }
}

    public function destroy(Invoice $invoice) : RedirectResponse
    {
        $invoice->delete();
        return redirect()->route('invoices.index')
                ->withSuccess('Invoice is deleted successfully.');
    }
}
