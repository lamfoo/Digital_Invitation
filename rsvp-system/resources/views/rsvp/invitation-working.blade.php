<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convite - {{ $guest->event->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
            font-family: 'Roboto', sans-serif;
        }
        
        .invitation-card {
            max-width: 650px;
            margin: 2rem auto;
            padding: 2rem;
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        
        .elegant-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
        }
        
        .event-detail {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            margin: 1rem 0;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .rsvp-btn {
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            border: none;
            margin: 0.5rem;
            min-width: 200px;
        }
        
        .rsvp-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }
        
        .btn-rsvp-yes {
            background: linear-gradient(45deg, #10b981, #059669);
            color: white;
        }
        
        .btn-rsvp-no {
            background: linear-gradient(45deg, #ef4444, #dc2626);
            color: white;
        }
        
        .btn-rsvp-maybe {
            background: linear-gradient(45deg, #f59e0b, #d97706);
            color: white;
        }
        
        @media (max-width: 768px) {
            .invitation-card {
                margin: 1rem;
                padding: 1.5rem;
            }
            .rsvp-btn {
                width: 100%;
                margin: 0.25rem 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="invitation-card">
            <div class="text-center">
                <!-- Header -->
                <h1 class="elegant-title display-4 mb-3">Você foi convidado!</h1>
                <h3 class="mb-4">Caro(a) {{ $guest->name }},</h3>

                <!-- Event Details -->
                <div class="event-detail text-center">
                    <h2 class="elegant-title h3 mb-4">{{ $guest->event->title }}</h2>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <i class="bi bi-calendar3" style="font-size: 1.5rem;"></i>
                            <br><strong>{{ $guest->event->event_date->format('d/m/Y') }}</strong>
                            <br><span>{{ $guest->event->event_time->format('H:i') }}</span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <i class="bi bi-geo-alt" style="font-size: 1.5rem;"></i>
                            <br><strong>{{ $guest->event->location }}</strong>
                        </div>
                    </div>

                    @if($guest->event->description)
                    <div class="mt-3">
                        <p>{{ $guest->event->description }}</p>
                    </div>
                    @endif
                </div>

                <!-- Messages -->
                @if(session('success'))
                    <div class="alert alert-success mb-4">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                    </div>
                    
                    <div class="event-detail">
                        <h4>Sua Resposta:</h4>
                        @php $freshGuest = $guest->fresh(); @endphp
                        @switch($freshGuest->rsvp_status)
                            @case('yes')
                                <span class="badge bg-success fs-5">
                                    <i class="bi bi-check-circle"></i> Vou comparecer
                                </span>
                                @break
                            @case('no')
                                <span class="badge bg-danger fs-5">
                                    <i class="bi bi-x-circle"></i> Não posso comparecer
                                </span>
                                @break
                            @case('maybe')
                                <span class="badge bg-warning fs-5">
                                    <i class="bi bi-question-circle"></i> Talvez
                                </span>
                                @break
                        @endswitch
                        <br><small class="text-white-50 mt-2">
                            Respondido em {{ $freshGuest->rsvp_confirmed_at ? $freshGuest->rsvp_confirmed_at->format('d/m/Y H:i') : 'agora' }}
                        </small>
                    </div>
                @elseif(session('error'))
                    <div class="alert alert-danger mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-warning mb-4">
                        <strong>Erros encontrados:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- RSVP Form -->
                @if(!session('success'))
                    <h4 class="mb-4">Você vai comparecer?</h4>
                    
                    <form action="{{ route('rsvp.submit', $guest->unique_link_token) }}" method="POST" id="rsvpForm">
                        @csrf
                        
                        <div class="d-grid gap-3">
                            <button type="submit" name="rsvp_status" value="yes" class="btn rsvp-btn btn-rsvp-yes">
                                <i class="bi bi-check-circle"></i> SIM, vou comparecer
                            </button>
                            <button type="submit" name="rsvp_status" value="no" class="btn rsvp-btn btn-rsvp-no">
                                <i class="bi bi-x-circle"></i> NÃO, não posso comparecer
                            </button>
                            <button type="submit" name="rsvp_status" value="maybe" class="btn rsvp-btn btn-rsvp-maybe">
                                <i class="bi bi-question-circle"></i> TALVEZ
                            </button>
                        </div>
                    </form>

                    <div class="mt-4">
                        <small class="text-white-50">
                            <i class="bi bi-clock"></i> Por favor, responda até {{ $guest->event->rsvp_expiration_at->format('d/m/Y') }}
                        </small>
                    </div>
                @endif

                <!-- Footer -->
                <div class="text-center mt-4">
                    <small class="text-white-50">
                        Esperamos celebrar com você!
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple form submission with feedback
        document.getElementById('rsvpForm')?.addEventListener('submit', function(e) {
            const button = e.submitter;
            
            // Disable button to prevent double-click
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';
            
            // Show confirmation
            const status = button.value;
            const statusText = status === 'yes' ? 'comparecendo' : 
                             status === 'no' ? 'não comparecendo' : 'talvez comparecendo';
            
            if (!confirm('Confirmar que você estará ' + statusText + '?')) {
                e.preventDefault();
                button.disabled = false;
                button.innerHTML = button.getAttribute('data-original') || 'Confirmar';
                return false;
            }
        });
    </script>
</body>
</html>