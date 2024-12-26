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

        foreach ($payments as $payment) {
            // Kiểm tra xem payment có liên kết với booking hay không
            if ($payment->booking) {
                // Tạo số hóa đơn với định dạng INV-000001, INV-000002, ...
                $invoiceNumber = 'INV-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT);
                
                // Lấy thời gian tạo của booking
                $paymentCreatedAt = Carbon::parse($payment->created_at);
                
                // Thêm từ 1 đến 2 phút vào thời gian tạo của booking
                $invoiceCreatedAt = $paymentCreatedAt->copy()->addMinutes(rand(1, 2));
                
                // Tạo dữ liệu invoice
                $invoices[] = [
                    'payment_id'     => $payment->id,
                    'invoice_number' => $invoiceNumber,
                    'total_amount'   => $payment->amount,
                    'created_at'     => $invoiceCreatedAt,
                    'updated_at'     => $invoiceCreatedAt,
                ];
            }
        }

        // Chèn tất cả các invoice một cách hiệu quả
        Invoice::insert($invoices);
    }
}
