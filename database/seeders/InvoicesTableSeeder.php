<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Invoice;
use Carbon\Carbon;

class InvoicesTableSeeder extends Seeder
{
    public function run()
    {
        // Lấy tất cả các payment cùng với booking liên quan để tối ưu truy vấn
        $payments = Payment::with('booking')->get();

        $invoices = [];
        // Khởi tạo số hóa đơn bắt đầu từ 1000000000000000
        $currentInvoiceNumber = 1000000000000000;

        foreach ($payments as $payment) {
            // Kiểm tra xem Payment có liên kết với Booking hay không
            if ($payment->booking) {
                    // Gán số hóa đơn và tăng số đếm
                    $invoiceNumber = (string)$currentInvoiceNumber;
                    $currentInvoiceNumber++;

                    // Lấy thời gian tạo của Payment
                    $paymentCreatedAt = Carbon::parse($payment->created_at);

                    // Thêm từ 1 đến 2 phút vào thời gian tạo của Payment cho Invoice
                    $invoiceCreatedAt = $paymentCreatedAt->copy()->addMinutes(rand(1, 2));

                    // Tạo dữ liệu Invoice
                    $invoices[] = [
                        'payment_id'     => $payment->id,
                        'invoice_number' => $invoiceNumber,
                        'total_amount'   => $payment->amount,
                        'created_at'     => $invoiceCreatedAt,
                        'updated_at'     => $invoiceCreatedAt,
                    ];
            }
        }

        // Chèn tất cả các Invoice vào database một cách hiệu quả
        if (!empty($invoices)) {
            Invoice::insert($invoices);
        }
    }
}
