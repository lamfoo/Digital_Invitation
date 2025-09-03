<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Guest extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'event_id',
        'name',
        'unique_link_token',
        'rsvp_status',
        'rsvp_confirmed_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'rsvp_confirmed_at' => 'datetime',
    ];

    /**
     * Boot method to generate unique token on creation.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($guest) {
            if (empty($guest->unique_link_token)) {
                $guest->unique_link_token = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the event that this guest belongs to.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Check if the guest has already responded to the RSVP.
     */
    public function hasResponded(): bool
    {
        return $this->rsvp_status !== 'pending';
    }

    /**
     * Check if the RSVP link is still valid (not expired).
     */
    public function isInvitationValid(): bool
    {
        return $this->event->isRsvpOpen();
    }

    /**
     * Get the invitation URL.
     */
    public function getInvitationUrlAttribute(): string
    {
        return url('/invite/' . $this->unique_link_token);
    }

    /**
     * Scope to find guest by token.
     */
    public function scopeByToken($query, $token)
    {
        return $query->where('unique_link_token', $token);
    }
}
