<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventLocation extends Model
{
     protected $fillable = [
        'event_id',
        'place_id',
        'osm_id',
        'osm_type',
        'latitude',
        'longitude',
        'display_name',
        'display_place',
        'display_address',
        'country',
        'country_code',
        'type',
        'class',
        'bounds'
    ];

    protected $casts = [
        'bounds' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
