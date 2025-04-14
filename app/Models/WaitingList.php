<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaitingList extends Model
{

    protected $fillable = [
        'ticket_id',
        'user_id',
        'quantity_requested',
        'status',
        'notified_at',
        'converted_at'
    ];

    protected $casts = [
        'quantity_requested' => 'integer',
        'notified_at' => 'datetime',
        'converted_at' => 'datetime'
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsNotified()
    {
        $this->update([
            'status' => 'notified',
            'notified_at' => now()
        ]);
    }

    public function markAsConverted()
    {
        $this->update([
            'status' => 'converted',
            'converted_at' => now()
        ]);
    }
}
