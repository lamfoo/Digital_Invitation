@extends('layouts.admin')

@section('title', 'Edit Event')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card admin-card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-pencil"></i> Edit Event: {{ $event->title }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.events.update', $event) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="title" class="form-label">Event Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $event->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                   id="location" name="location" value="{{ old('location', $event->location) }}" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="event_date" class="form-label">Event Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('event_date') is-invalid @enderror" 
                                   id="event_date" name="event_date" value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required>
                            @error('event_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="event_time" class="form-label">Event Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('event_time') is-invalid @enderror" 
                                   id="event_time" name="event_time" value="{{ old('event_time', $event->event_time->format('H:i')) }}" required>
                            @error('event_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $event->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="rsvp_expiration_at" class="form-label">RSVP Deadline <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('rsvp_expiration_at') is-invalid @enderror" 
                                   id="rsvp_expiration_at" name="rsvp_expiration_at" 
                                   value="{{ old('rsvp_expiration_at', $event->rsvp_expiration_at->format('Y-m-d')) }}" required>
                            <div class="form-text">Guests won't be able to RSVP after this date.</div>
                            @error('rsvp_expiration_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Events
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Update Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection