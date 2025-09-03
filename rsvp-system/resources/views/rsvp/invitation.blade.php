@extends('layouts.public')

@section('title', 'You\'re Invited!')

@section('content')
<div class="invitation-card">
    <div class="invitation-content">
        <!-- Header -->
        <div class="text-center mb-4">
            <h1 class="elegant-title display-4 mb-2">You're Invited!</h1>
            <p class="h5 mb-0">Dear {{ $guest->name }},</p>
        </div>

        <!-- Event Details -->
        <div class="event-detail text-center">
            <h2 class="elegant-title h3 mb-3">{{ $guest->event->title }}</h2>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="bi bi-calendar3 me-2" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong>{{ $guest->event->event_date->format('l, F j, Y') }}</strong><br>
                            <span>{{ $guest->event->event_time->format('g:i A') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="bi bi-geo-alt me-2" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong>{{ $guest->event->location }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            @if($guest->event->description)
            <div class="mt-3">
                <p class="mb-0">{{ $guest->event->description }}</p>
            </div>
            @endif
        </div>

        <!-- RSVP Section -->
        <div class="rsvp-buttons text-center">
            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                </div>
                
                <div class="mt-3">
                    <p class="h5">Your Response: 
                        @switch($guest->rsvp_status)
                            @case('yes')
                                <span class="badge bg-success fs-6">Attending</span>
                                @break
                            @case('no')
                                <span class="badge bg-danger fs-6">Not Attending</span>
                                @break
                            @case('maybe')
                                <span class="badge bg-warning fs-6">Maybe</span>
                                @break
                        @endswitch
                    </p>
                    <small class="text-white-50">Responded on {{ $guest->rsvp_confirmed_at->format('M d, Y \a\t g:i A') }}</small>
                </div>
            @else
                <h4 class="mb-3">Will you be attending?</h4>
                
                <form action="{{ route('rsvp.submit', $guest->unique_link_token) }}" method="POST" id="rsvpForm">
                    @csrf
                    <div class="d-flex flex-column flex-md-row justify-content-center gap-2">
                        <button type="submit" name="rsvp_status" value="yes" class="btn rsvp-btn btn-rsvp-yes">
                            <i class="bi bi-check-circle"></i> Yes, I will attend
                        </button>
                        <button type="submit" name="rsvp_status" value="no" class="btn rsvp-btn btn-rsvp-no">
                            <i class="bi bi-x-circle"></i> No, I cannot attend
                        </button>
                        <button type="submit" name="rsvp_status" value="maybe" class="btn rsvp-btn btn-rsvp-maybe">
                            <i class="bi bi-question-circle"></i> Maybe
                        </button>
                    </div>
                </form>

                <div class="mt-3">
                    <small class="text-white-50">
                        <i class="bi bi-clock"></i> Please respond by {{ $guest->event->rsvp_expiration_at->format('F j, Y') }}
                    </small>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="text-center mt-4">
            <small class="text-white-50">
                We look forward to celebrating with you!
            </small>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('rsvpForm')?.addEventListener('submit', function(e) {
    const button = e.submitter;
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
});
</script>
@endsection