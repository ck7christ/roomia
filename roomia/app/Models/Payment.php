<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $fillable = [
        'booking_id',
        'amount',
        'currency',
        'method',
        'provider_transaction_id',
        'status',
        'meta',
        'paid_at',

        'refunded_at',
        'refund_id',
        'refund_amount',
    ];

    protected $casts = [
        'meta' => 'array',
        'paid_at' => 'datetime',

        'refunded_at' => 'datetime',
    ];

    // Method constants
    public const METHOD_COD = 'cod';
    public const METHOD_STRIPE = 'stripe';


    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED = 'failed';
    public const STATUS_REFUNDED = 'refunded';

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
