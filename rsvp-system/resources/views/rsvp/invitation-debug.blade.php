<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSVP - {{ $guest->event->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
            font-family: 'Arial', sans-serif;
        }
        .invitation-card {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }
        .rsvp-btn {
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: bold;
            margin: 10px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="invitation-card">
            <div class="text-center">
                <h1 class="mb-4">You're Invited!</h1>
                <h3 class="mb-2">Dear {{ $guest->name }},</h3>
                <h2 class="mb-4">{{ $guest->event->title }}</h2>
                
                <div class="mb-4">
                    <p><i class="bi bi-calendar"></i> {{ $guest->event->event_date->format('F j, Y') }}</p>
                    <p><i class="bi bi-clock"></i> {{ $guest->event->event_time->format('g:i A') }}</p>
                    <p><i class="bi bi-geo-alt"></i> {{ $guest->event->location }}</p>
                </div>

                <!-- Debug Information -->
                <div class="alert alert-info">
                    <strong>Debug Info:</strong><br>
                    Guest ID: {{ $guest->id }}<br>
                    Token: {{ $guest->unique_link_token }}<br>
                    Current Status: {{ $guest->rsvp_status }}<br>
                    Has Responded: {{ $guest->hasResponded() ? 'Yes' : 'No' }}<br>
                    Invitation Valid: {{ $guest->isInvitationValid() ? 'Yes' : 'No' }}<br>
                    Form Action: {{ route('rsvp.submit', $guest->unique_link_token) }}<br>
                    CSRF Token: {{ csrf_token() }}
                </div>

                <!-- Messages -->
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle"></i> {{ session('success') }}
                        <br><strong>New Status:</strong> {{ $guest->fresh()->rsvp_status }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="bi bi-x-circle"></i> {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-warning">
                        <strong>Validation Errors:</strong>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- RSVP Form -->
                @if(!$guest->hasResponded() && $guest->isInvitationValid())
                    <h4 class="mb-3">Will you be attending?</h4>
                    
                    <form action="{{ route('rsvp.submit', $guest->unique_link_token) }}" method="POST" style="display: inline-block;">
                        @csrf
                        <div class="d-grid gap-2">
                            <button type="submit" name="rsvp_status" value="yes" class="btn btn-success rsvp-btn">
                                <i class="bi bi-check-circle"></i> YES - I will attend
                            </button>
                            <button type="submit" name="rsvp_status" value="no" class="btn btn-danger rsvp-btn">
                                <i class="bi bi-x-circle"></i> NO - I cannot attend
                            </button>
                            <button type="submit" name="rsvp_status" value="maybe" class="btn btn-warning rsvp-btn">
                                <i class="bi bi-question-circle"></i> MAYBE - I'm not sure
                            </button>
                        </div>
                    </form>

                    <!-- Manual test buttons for debugging -->
                    <div class="mt-4">
                        <h6>Manual Test (for debugging):</h6>
                        <div class="btn-group">
                            <a href="{{ route('rsvp.submit', $guest->unique_link_token) }}?rsvp_status=yes&_token={{ csrf_token() }}" 
                               class="btn btn-outline-light btn-sm">Test Yes (GET)</a>
                            <a href="{{ route('rsvp.submit', $guest->unique_link_token) }}?rsvp_status=no&_token={{ csrf_token() }}" 
                               class="btn btn-outline-light btn-sm">Test No (GET)</a>
                        </div>
                    </div>
                @else
                    @if($guest->hasResponded())
                        <div class="alert alert-info">
                            <h5>Thank you for responding!</h5>
                            <p>Your response: <strong>{{ ucfirst($guest->rsvp_status) }}</strong></p>
                            <p>Submitted: {{ $guest->rsvp_confirmed_at->format('M d, Y g:i A') }}</p>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <h5>Invitation Expired</h5>
                            <p>The RSVP deadline has passed.</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Debug form submission
        document.querySelector('form')?.addEventListener('submit', function(e) {
            console.log('Form submission started');
            console.log('Action:', this.action);
            console.log('Method:', this.method);
            
            const formData = new FormData(this);
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
        });
    </script>
</body>
</html>