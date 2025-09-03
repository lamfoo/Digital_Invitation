<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Convite - {{ $guest->event->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        
        .invitation-card {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            background: rgba(255,255,255,0.15);
            border-radius: 20px;
            text-align: center;
        }
        
        .rsvp-btn {
            padding: 15px 25px;
            margin: 10px;
            border-radius: 25px;
            border: none;
            font-size: 16px;
            font-weight: bold;
            min-width: 180px;
            cursor: pointer;
        }
        
        .btn-yes { background: #28a745; color: white; }
        .btn-no { background: #dc3545; color: white; }
        .btn-maybe { background: #ffc107; color: black; }
        
        .rsvp-btn:hover { opacity: 0.9; transform: scale(1.05); }
    </style>
</head>
<body>
    <div class="invitation-card">
        <h1>🎉 Você foi convidado!</h1>
        <h2>Caro(a) {{ $guest->name }},</h2>
        
        <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 15px; margin: 20px 0;">
            <h3>{{ $guest->event->title }}</h3>
            <p><i class="bi bi-calendar"></i> {{ $guest->event->event_date->format('d/m/Y') }} às {{ $guest->event->event_time->format('H:i') }}</p>
            <p><i class="bi bi-geo-alt"></i> {{ $guest->event->location }}</p>
            @if($guest->event->description)
                <p>{{ $guest->event->description }}</p>
            @endif
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                <h4>✅ {{ session('success') }}</h4>
                <p>Sua resposta foi registrada com sucesso!</p>
                @php $freshGuest = $guest->fresh(); @endphp
                <p><strong>Sua resposta:</strong> 
                    @if($freshGuest->rsvp_status === 'yes') ✅ Vou comparecer
                    @elseif($freshGuest->rsvp_status === 'no') ❌ Não posso comparecer  
                    @else ❓ Talvez
                    @endif
                </p>
            </div>
        @else
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-warning">
                    <strong>Erros:</strong>
                    @foreach($errors->all() as $error)
                        <br>• {{ $error }}
                    @endforeach
                </div>
            @endif

            <h3>Você vai comparecer?</h3>
            
            <!-- Three separate simple forms -->
            <div>
                <form method="POST" action="{{ route('rsvp.submit', $guest->unique_link_token) }}" style="display: inline-block; margin: 10px;">
                    @csrf
                    <input type="hidden" name="rsvp_status" value="yes">
                    <button type="submit" class="rsvp-btn btn-yes" onclick="return confirm('Confirmar presença: SIM, vou comparecer?')">
                        ✅ SIM, vou comparecer
                    </button>
                </form>

                <form method="POST" action="{{ route('rsvp.submit', $guest->unique_link_token) }}" style="display: inline-block; margin: 10px;">
                    @csrf
                    <input type="hidden" name="rsvp_status" value="no">
                    <button type="submit" class="rsvp-btn btn-no" onclick="return confirm('Confirmar: NÃO posso comparecer?')">
                        ❌ NÃO, não posso comparecer
                    </button>
                </form>

                <form method="POST" action="{{ route('rsvp.submit', $guest->unique_link_token) }}" style="display: inline-block; margin: 10px;">
                    @csrf
                    <input type="hidden" name="rsvp_status" value="maybe">
                    <button type="submit" class="rsvp-btn btn-maybe" onclick="return confirm('Confirmar: TALVEZ compareça?')">
                        ❓ TALVEZ
                    </button>
                </form>
            </div>

            <p style="margin-top: 20px;">
                <small>Responda até: {{ $guest->event->rsvp_expiration_at->format('d/m/Y') }}</small>
            </p>
        @endif

        <hr style="margin: 30px 0; border-color: rgba(255,255,255,0.3);">
        <p><small>Esperamos celebrar com você! 🎉</small></p>
    </div>
</body>
</html>