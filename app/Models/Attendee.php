<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendee extends Model
{
     protected $fillable = [
        'booking_id',
        'ticket_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'custom_fields',
        'check_in_status',
        'check_in_time'
    ];

    protected $casts = [
        'custom_fields' => 'array',
        'check_in_time' => 'datetime'
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function checkIn(): void
    {
        $this->update([
            'check_in_status' => true,
            'check_in_time' => now()
        ]);
    }
}
