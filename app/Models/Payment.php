<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'transaction_id',
        'amount',
        'payment_method',
        'payment_status',
        'created_at',
    ];

    /**
     * Một payment thuộc về một booking.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Một payment có một invoice.
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
