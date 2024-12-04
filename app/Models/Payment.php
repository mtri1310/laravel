<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_id',
        'booking_id',
        'transaction_id',
        'amount',
        'payment_method',
        'payment_status',
        'created_at'
    ];
}
