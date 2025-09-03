@extends('layouts.admin')

@section('title', 'RSVPs - ' . $event->title)

@section('header-actions')
    <div class="btn-group">
        <a href="{{ route('admin.events.guests.index', $event) }}" class="btn btn-info">
            <i class="bi bi-people"></i> Manage Guests
        </a>
        <a href="{{ route('admin.events.rsvps.export', $event) }}" class="btn btn-success">
            <i class="bi bi-download"></i> Export CSV
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Event Info -->
        <div class="card admin-card mb-4">
            <div class="card-body">
                <h5 class="elegant-title text-primary">{{ $event->title }}</h5>
                <p class="text-muted mb-0">
                    <i class="bi bi-geo-alt"></i> {{ $event->location }} • 
                    <i class="bi bi-calendar"></i> {{ $event->event_date->format('M d, Y') }} at {{ $event->event_time->format('g:i A') }}
                </p>
            </div>
        </div>

        <!-- RSVP Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card admin-card">
                    <div class="card-body text-center">
                        <div class="stats-card bg-success">
                            <h3>{{ $stats['confirmed'] }}</h3>
                            <small>Confirmed</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card admin-card">
                    <div class="card-body text-center">
                        <div class="stats-card bg-danger">
                            <h3>{{ $stats['declined'] }}</h3>
                            <small>Declined</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card admin-card">
                    <div class="card-body text-center">
                        <div class="stats-card bg-warning">
                            <h3>{{ $stats['maybe'] }}</h3>
                            <small>Maybe</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card admin-card">
                    <div class="card-body text-center">
                        <div class="stats-card bg-secondary">
                            <h3>{{ $stats['pending'] }}</h3>
                            <small>Pending</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RSVP List -->
        <div class="card admin-card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-list-check"></i> RSVP Responses</h5>
                <span class="badge bg-light text-dark">{{ $stats['total'] }} total</span>
            </div>
            <div class="card-body">
                @if($guests->count() > 0)
                    <!-- Filter tabs -->
                    <ul class="nav nav-tabs mb-3" id="rsvpTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                                All ({{ $stats['total'] }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="confirmed-tab" data-bs-toggle="tab" data-bs-target="#confirmed" type="button" role="tab">
                                Confirmed ({{ $stats['confirmed'] }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="declined-tab" data-bs-toggle="tab" data-bs-target="#declined" type="button" role="tab">
                                Declined ({{ $stats['declined'] }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="maybe-tab" data-bs-toggle="tab" data-bs-target="#maybe" type="button" role="tab">
                                Maybe ({{ $stats['maybe'] }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                                Pending ({{ $stats['pending'] }})
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="rsvpTabContent">
                        <!-- All RSVPs -->
                        <div class="tab-pane fade show active" id="all" role="tabpanel">
                            @include('admin.guests.partials.rsvp-table', ['guestList' => $guests])
                        </div>

                        <!-- Confirmed -->
                        <div class="tab-pane fade" id="confirmed" role="tabpanel">
                            @include('admin.guests.partials.rsvp-table', ['guestList' => $guests->where('rsvp_status', 'yes')])
                        </div>

                        <!-- Declined -->
                        <div class="tab-pane fade" id="declined" role="tabpanel">
                            @include('admin.guests.partials.rsvp-table', ['guestList' => $guests->where('rsvp_status', 'no')])
                        </div>

                        <!-- Maybe -->
                        <div class="tab-pane fade" id="maybe" role="tabpanel">
                            @include('admin.guests.partials.rsvp-table', ['guestList' => $guests->where('rsvp_status', 'maybe')])
                        </div>

                        <!-- Pending -->
                        <div class="tab-pane fade" id="pending" role="tabpanel">
                            @include('admin.guests.partials.rsvp-table', ['guestList' => $guests->where('rsvp_status', 'pending')])
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-people display-1 text-muted"></i>
                        <h3 class="mt-3 text-muted">No Guests Added</h3>
                        <p class="text-muted">Add guests to see RSVP responses here</p>
                        <a href="{{ route('admin.events.guests.index', $event) }}" class="btn btn-primary">
                            <i class="bi bi-people"></i> Manage Guests
                        </a>
                    </div>
                @endif
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