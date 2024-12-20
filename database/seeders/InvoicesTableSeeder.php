<?php 

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Invoice;

class InvoicesTableSeeder extends Seeder
{
    public function run()
    {
        $payments = Payment::all();

        $payments->each(function ($payment) {
            // Tạo số hóa đơn
            $invoiceNumber = 'INV-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT);
            
            // Tạo invoice cho mỗi payment
            Invoice::create([
                'payment_id'    => $payment->id,
                'invoice_number'=> $invoiceNumber,
                'total_amount'  => $payment->amount, // Số tiền trong hóa đơn giống payment
                'created_at'    => now(),
            ]);
        });
    }
}
