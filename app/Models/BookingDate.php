<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingDate extends Model
{
    protected $fillable = [
        'booking_id',
        'event_date'
    ];

    protected $casts = [
        'event_date' => 'date'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
