@extends('layouts.public')

@section('title', 'Invitation Expired')

@section('content')
<div class="invitation-card" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
    <div class="invitation-content text-center">
        <div class="mb-4">
            <i class="bi bi-clock-history display-1 mb-3"></i>
            <h1 class="elegant-title h2 mb-3">Invitation Expired</h1>
        </div>

        <div class="event-detail">
            <h3 class="elegant-title mb-3">{{ $guest->event->title }}</h3>
            <p class="mb-2">
                <i class="bi bi-person me-2"></i>
                Dear {{ $guest->name }},
            </p>
            <p class="mb-0">
                Unfortunately, the RSVP deadline for this event has passed.
                The deadline was {{ $guest->event->rsvp_expiration_at->format('F j, Y \a\t g:i A') }}.
            </p>
        </div>

        <div class="mt-4">
            <h5>Event Details</h5>
            <p class="mb-1">
                <i class="bi bi-calendar3 me-2"></i>
                {{ $guest->event->event_date->format('l, F j, Y') }} at {{ $guest->event->event_time->format('g:i A') }}
            </p>
            <p class="mb-1">
                <i class="bi bi-geo-alt me-2"></i>
                {{ $guest->event->location }}
            </p>
        </div>

        <div class="mt-4">
            <small class="text-white-50">
                If you need to make changes to your attendance, please contact the event organizer directly.
            </small>
        </div>
    </div>
</div>
@endsection