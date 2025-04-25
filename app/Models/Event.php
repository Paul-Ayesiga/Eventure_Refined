<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    protected $fillable = [
        'organisation_id',
        'event_type',
        'name',
        'venue',
        'event_repeat',
        'repeat_days',
        'start_date',
        'end_date',
        'start_datetime',
        'end_datetime',
        'timezone',
        'currency',
        'status',
        'category',
        'auto_convert_timezone',
        'description',
        'tags',
        'banners',
        'is_archived',
        'archived_at'
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'start_date' => 'date',
        'end_date' => 'date',
        'auto_convert_timezone' => 'boolean',
        'repeat_days' => 'integer',
        'tags' => 'array',
        'banners' => 'array',
        'is_archived' => 'boolean',
        'archived_at' => 'datetime'
    ];

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    // Bookings for the event
    // This is a one-to-many relationship, as an event can have multiple bookings
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'event_id');
    }

     // Attendees that booked the event (many-to-many through bookings)
    public function attendees(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'bookings');
    }

    // Event location
    // This is a one-to-one relationship, as an event can have only one location
    // but a location can be associated with multiple events
    public function location(): HasOne
    {
        return $this->hasOne(EventLocation::class, 'event_id');
    }

    // Event tickets
    // This is a one-to-many relationship, as an event can have multiple tickets
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'event_id');
    }

    // Event settings
    // This is a one-to-one relationship, as an event can have only one set of settings
    public function settings(): HasOne
    {
        return $this->hasOne(EventSetting::class, 'event_id');
    }

    // For backward compatibility
    public function organiser(): BelongsTo
    {
        return $this->organisation();
    }

    /**
     * Check if the event has passed its end date
     *
     * @param bool $includeBuffer Whether to include a one-day buffer after the event ends
     * @return bool
     */
    public function isPast(bool $includeBuffer = false): bool
    {
        $compareDate = $includeBuffer ? now()->subDay() : now();

        return $this->end_date
            ? $compareDate->isAfter($this->end_date)
            : $compareDate->isAfter($this->start_date);
    }

    /**
     * Check if the event is archived
     */
    public function isArchived(): bool
    {
        return $this->is_archived;
    }

    /**
     * Archive the event
     *
     * Sets the event as archived and changes status to Draft
     */
    public function archive(): void
    {
        $this->is_archived = true;
        $this->archived_at = now();
        $this->status = 'Draft'; // Change status back to Draft when archived
        $this->save();
    }

    /**
     * Check if the event has been archived for more than 30 days
     */
    public function isReadyForDeletion(): bool
    {
        return $this->is_archived && $this->archived_at && $this->archived_at->addDays(30)->isPast();
    }
}
