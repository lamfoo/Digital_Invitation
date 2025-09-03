<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'location',
        'event_date',
        'event_time',
        'description',
        'rsvp_expiration_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime:H:i',
        'rsvp_expiration_at' => 'datetime',
    ];

    /**
     * Get all guests for this event.
     */
    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    /**
     * Get guests who have confirmed attendance.
     */
    public function confirmedGuests(): HasMany
    {
        return $this->hasMany(Guest::class)->where('rsvp_status', 'yes');
    }

    /**
     * Get guests who have declined attendance.
     */
    public function declinedGuests(): HasMany
    {
        return $this->hasMany(Guest::class)->where('rsvp_status', 'no');
    }

    /**
     * Get guests who are unsure about attendance.
     */
    public function maybeGuests(): HasMany
    {
        return $this->hasMany(Guest::class)->where('rsvp_status', 'maybe');
    }

    /**
     * Get pending RSVPs.
     */
    public function pendingRsvps(): HasMany
    {
        return $this->hasMany(Guest::class)->where('rsvp_status', 'pending');
    }

    /**
     * Check if RSVP is still open.
     */
    public function isRsvpOpen(): bool
    {
        return now()->isBefore($this->rsvp_expiration_at);
    }
}
