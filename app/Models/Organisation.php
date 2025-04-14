<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Organisation extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone_number',
        'description',
        'website',
        'logo_url',
        'country',
        'currency',
        'socials'
    ];

    protected $casts = [
        'socials' => 'array'
    ];

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
