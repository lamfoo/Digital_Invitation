@extends('layouts.admin')

@section('title', $event->title)

@section('header-actions')
    <div class="btn-group">
        <a href="{{ route('admin.events.guests.index', $event) }}" class="btn btn-info">
            <i class="bi bi-people"></i> Manage Guests
        </a>
        <a href="{{ route('admin.events.rsvps', $event) }}" class="btn btn-success">
            <i class="bi bi-list-check"></i> View RSVPs
        </a>
        <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Edit Event
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card admin-card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Event Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <h2 class="elegant-title text-primary">{{ $event->title }}</h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong><i class="bi bi-geo-alt text-danger"></i> Location:</strong><br>
                        {{ $event->location }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong><i class="bi bi-calendar text-primary"></i> Date & Time:</strong><br>
                        {{ $event->event_date->format('F j, Y') }} at {{ $event->event_time->format('g:i A') }}
                    </div>
                </div>

                @if($event->description)
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <strong><i class="bi bi-info-circle text-info"></i> Description:</strong><br>
                        <p class="mt-2">{{ $event->description }}</p>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <strong><i class="bi bi-clock text-warning"></i> RSVP Deadline:</strong><br>
                        <span class="{{ $event->isRsvpOpen() ? 'text-success' : 'text-danger' }}">
                            {{ $event->rsvp_expiration_at->format('F j, Y \a\t g:i A') }}
                            @if($event->isRsvpOpen())
                                (Open)
                            @else
                                (Expired)
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card admin-card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> RSVP Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="stats-card bg-success">
                            <h3>{{ $event->confirmed_guests_count }}</h3>
                            <small>Confirmed</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stats-card bg-danger">
                            <h3>{{ $event->declined_guests_count }}</h3>
                            <small>Declined</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stats-card bg-warning">
                            <h3>{{ $event->maybe_guests_count }}</h3>
                            <small>Maybe</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stats-card bg-secondary">
                            <h3>{{ $event->pending_rsvps_count }}</h3>
                            <small>Pending</small>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <h4 class="text-primary">{{ $event->guests_count }}</h4>
                    <small class="text-muted">Total Guests</small>
                </div>
            </div>
        </div>

        <div class="card admin-card mt-3">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-link-45deg"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.events.guests.index', $event) }}" class="btn btn-outline-info">
                        <i class="bi bi-people"></i> Manage Guests
                    </a>
                    <a href="{{ route('admin.events.rsvps', $event) }}" class="btn btn-outline-success">
                        <i class="bi bi-list-check"></i> View RSVPs
                    </a>
                    <a href="{{ route('admin.events.rsvps.export', $event) }}" class="btn btn-outline-primary">
                        <i class="bi bi-download"></i> Export CSV
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="d-flex justify-content-center">
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Events
            </a>
        </div>
    </div>
</div>
@endsection