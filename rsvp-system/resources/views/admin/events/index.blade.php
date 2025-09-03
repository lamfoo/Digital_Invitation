@extends('layouts.admin')

@section('title', 'Events')

@section('header-actions')
    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Create Event
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card admin-card">
            <div class="card-body">
                @if($events->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>Event</th>
                                    <th>Date & Time</th>
                                    <th>Location</th>
                                    <th>RSVP Status</th>
                                    <th>Expires</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $event)
                                <tr>
                                    <td>
                                        <strong>{{ $event->title }}</strong>
                                        @if($event->description)
                                            <br><small class="text-muted">{{ Str::limit($event->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $event->event_date->format('M d, Y') }}</strong><br>
                                        <small class="text-muted">{{ $event->event_time->format('g:i A') }}</small>
                                    </td>
                                    <td>{{ $event->location }}</td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            <span class="badge bg-success">{{ $event->confirmed_guests_count }} Yes</span>
                                            <span class="badge bg-danger">{{ $event->declined_guests_count }} No</span>
                                            <span class="badge bg-warning">{{ $event->maybe_guests_count }} Maybe</span>
                                            <span class="badge bg-secondary">{{ $event->pending_rsvps_count }} Pending</span>
                                        </div>
                                        <small class="text-muted">{{ $event->guests_count }} total guests</small>
                                    </td>
                                    <td>
                                        <small class="{{ $event->isRsvpOpen() ? 'text-success' : 'text-danger' }}">
                                            {{ $event->rsvp_expiration_at->format('M d, Y g:i A') }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.events.show', $event) }}" class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.events.guests.index', $event) }}" class="btn btn-sm btn-outline-info" title="Guests">
                                                <i class="bi bi-people"></i>
                                            </a>
                                            <a href="{{ route('admin.events.rsvps', $event) }}" class="btn btn-sm btn-outline-success" title="RSVPs">
                                                <i class="bi bi-list-check"></i>
                                            </a>
                                            <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this event?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $events->links() }}
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x display-1 text-muted"></i>
                        <h3 class="mt-3 text-muted">No Events Yet</h3>
                        <p class="text-muted">Create your first event to get started!</p>
                        <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Create Event
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection