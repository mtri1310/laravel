<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'invoice_number',
        'total_amount',
        'created_at',
    ];

    /**
     * Một invoice thuộc về một payment.
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'payment_id', 'id');
    }
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i:s');
    }
}
