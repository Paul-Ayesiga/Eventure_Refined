<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
     protected $fillable = [
        'event_id',
        'name',
        'description',
        'price',
        'quantity_available',
        'quantity_sold',
        'sale_start_date',
        'sale_end_date',
        'max_tickets_per_booking',
        'status',
        'repeat_ticket'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity_available' => 'integer',
        'quantity_sold' => 'integer',
        'max_tickets_per_booking' => 'integer',
        'sale_start_date' => 'datetime',
        'sale_end_date' => 'datetime',
        'repeat_ticket' => 'boolean',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function bookingItems(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(Attendee::class);
    }

    public function waitingList(): HasMany
    {
        return $this->hasMany(WaitingList::class);
    }

    public function getWaitingListCount(): int
    {
        return $this->waitingList()->where('status', 'pending')->count();
    }

    public function addToWaitingList(User $user, int $quantity): WaitingList
    {
        // Check if user is already on the waiting list
        $existingEntry = $this->waitingList()
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($existingEntry) {
            // Update the existing entry with new quantity
            $existingEntry->update([
                'quantity_requested' => $quantity,
                'updated_at' => now()
            ]);
            return $existingEntry;
        }

        // Create new waiting list entry
        return $this->waitingList()->create([
            'user_id' => $user->id,
            'quantity_requested' => $quantity,
            'status' => 'pending'
        ]);
    }

    public function notifyNextInWaitingList(int $quantityAvailable): void
    {
        $waitingList = $this->waitingList()
            ->where('status', 'pending')
            ->orderBy('created_at')
            ->get();

        $remainingQuantity = $quantityAvailable;

        foreach ($waitingList as $entry) {
            if ($remainingQuantity <= 0) break;

            if ($entry->quantity_requested <= $remainingQuantity) {
                $entry->markAsNotified();
                $remainingQuantity -= $entry->quantity_requested;
            }
        }
    }

    public function isAvailable(): bool
    {
        return $this->quantity_available > $this->quantity_sold
            && $this->status === 'active'
            && now()->between($this->sale_start_date, $this->sale_end_date);
    }

    public function getRemainingQuantity(): int
    {
        return $this->quantity_available - $this->quantity_sold;
    }
}
