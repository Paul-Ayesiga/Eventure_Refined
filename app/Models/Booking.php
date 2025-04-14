<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
     protected $fillable = [
        'event_id',
        'user_id',
        'booking_reference',
        'status',
        'total_amount',
        'payment_status'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function userDetails()
    {
        return $this->hasOneThrough(UserDetail::class, User::class, 'id', 'user_id', 'user_id', 'id');
    }

    public function bookingItems(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(Attendee::class);
    }

    public function tickets(): BelongsToMany
    {
        return $this->belongsToMany(Ticket::class, 'booking_items')
            ->withPivot('quantity', 'unit_price', 'subtotal')
            ->withTimestamps();
    }

    public function dates(): HasMany
    {
        return $this->hasMany(BookingDate::class);
    }

    public function generateBookingReference(): string
    {
        return strtoupper(uniqid('BK'));
    }

    public function calculateTotalAmount(): float
    {
        return $this->bookingItems->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
    }
}
