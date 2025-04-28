<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlutterwaveTransaction extends Model
{
    protected $fillable = [
        'booking_id',
        'user_id',
        'amount',
        'currency',
        'display_currency',
        'exchange_rate',
        'display_amount',
        'tx_ref',
        'flw_ref',
        'status',
        'payment_type',
        'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'display_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'metadata' => 'array'
    ];

    /**
     * Get the booking associated with the transaction.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user associated with the transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a unique transaction reference.
     */
    public static function generateTxRef(): string
    {
        return 'FLW-' . uniqid() . '-' . time();
    }
}
