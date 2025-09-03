@extends('layouts.public')

@section('title', 'RSVP Already Submitted')

@section('content')
<div class="invitation-card" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
    <div class="invitation-content text-center">
        <div class="mb-4">
            <i class="bi bi-check-circle display-1 mb-3"></i>
            <h1 class="elegant-title h2 mb-3">RSVP Already Submitted</h1>
        </div>

        <div class="event-detail">
            <h3 class="elegant-title mb-3">{{ $guest->event->title }}</h3>
            <p class="mb-3">
                <i class="bi bi-person me-2"></i>
                Dear {{ $guest->name }},
            </p>
            <p class="mb-3">
                Thank you! You have already responded to this invitation.
            </p>
            
            <div class="mt-3 p-3 bg-white bg-opacity-20 rounded">
                <h5>Your Response:</h5>
                @switch($guest->rsvp_status)
                    @case('yes')
                        <span class="badge bg-success fs-6">
                            <i class="bi bi-check-circle"></i> Attending
                        </span>
                        @break
                    @case('no')
                        <span class="badge bg-danger fs-6">
                            <i class="bi bi-x-circle"></i> Not Attending
                        </span>
                        @break
                    @case('maybe')
                        <span class="badge bg-warning fs-6">
                            <i class="bi bi-question-circle"></i> Maybe
                        </span>
                        @break
                @endswitch
                <br>
                <small class="text-white-50">
                    Submitted on {{ $guest->rsvp_confirmed_at->format('F j, Y \a\t g:i A') }}
                </small>
            </div>
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
            
            @if($guest->event->description)
            <div class="mt-3">
                <p class="mb-0">{{ $guest->event->description }}</p>
            </div>
            @endif
        </div>

        <div class="mt-4">
            <small class="text-white-50">
                If you need to change your response, please contact the event organizer directly.
            </small>
        </div>
    </div>
</div>
@endsection