@extends('layouts.admin')

@section('title', 'Guests - ' . $event->title)

@section('header-actions')
    <div class="btn-group">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGuestModal">
            <i class="bi bi-person-plus"></i> Add Guest
        </button>
        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importCsvModal">
            <i class="bi bi-upload"></i> Import CSV
        </button>
        <a href="{{ route('admin.events.rsvps', $event) }}" class="btn btn-success">
            <i class="bi bi-list-check"></i> View RSVPs
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

        <!-- Guests List -->
        <div class="card admin-card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-people"></i> Guest List ({{ $guests->total() }} total)</h5>
            </div>
            <div class="card-body">
                @if($guests->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>RSVP Status</th>
                                    <th>Responded At</th>
                                    <th>Invitation Link</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($guests as $guest)
                                <tr>
                                    <td>
                                        <strong>{{ $guest->name }}</strong>
                                    </td>
                                    <td>
                                        @switch($guest->rsvp_status)
                                            @case('yes')
                                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Attending</span>
                                                @break
                                            @case('no')
                                                <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Not Attending</span>
                                                @break
                                            @case('maybe')
                                                <span class="badge bg-warning"><i class="bi bi-question-circle"></i> Maybe</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary"><i class="bi bi-clock"></i> Pending</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($guest->rsvp_confirmed_at)
                                            <small>{{ $guest->rsvp_confirmed_at->format('M d, Y g:i A') }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" 
                                                   value="{{ $guest->invitation_url }}" 
                                                   id="link-{{ $guest->id }}" readonly>
                                            <button class="btn btn-outline-secondary" type="button" 
                                                    onclick="copyToClipboard('link-{{ $guest->id }}')" title="Copy Link">
                                                <i class="bi bi-copy"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-warning" 
                                                    data-bs-toggle="modal" data-bs-target="#editGuestModal{{ $guest->id }}" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('admin.events.guests.destroy', [$event, $guest]) }}" method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to remove this guest?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Edit Guest Modal -->
                                <div class="modal fade" id="editGuestModal{{ $guest->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.events.guests.update', [$event, $guest]) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Guest</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="name{{ $guest->id }}" class="form-label">Guest Name</label>
                                                        <input type="text" class="form-control" id="name{{ $guest->id }}" 
                                                               name="name" value="{{ $guest->name }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update Guest</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{ $guests->links() }}
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-people display-1 text-muted"></i>
                        <h3 class="mt-3 text-muted">No Guests Yet</h3>
                        <p class="text-muted">Add guests manually or import from CSV</p>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGuestModal">
                                <i class="bi bi-person-plus"></i> Add Guest
                            </button>
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importCsvModal">
                                <i class="bi bi-upload"></i> Import CSV
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Guest Modal -->
<div class="modal fade" id="addGuestModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.events.guests.store', $event) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Guest</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="guestName" class="form-label">Guest Name</label>
                        <input type="text" class="form-control" id="guestName" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Guest</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import CSV Modal -->
<div class="modal fade" id="importCsvModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.events.guests.import', $event) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Import Guests from CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="csvFile" class="form-label">CSV File</label>
                        <input type="file" class="form-control" id="csvFile" name="csv_file" accept=".csv,.txt" required>
                        <div class="form-text">
                            CSV should have a header row with 'name' or 'Name' column.
                            <br><strong>Example:</strong>
                            <br>name
                            <br>John Doe
                            <br>Jane Smith
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Import Guests</button>
                </div>
            </form>
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

<script>
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    element.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(element.value);
    
    // Show feedback
    const button = element.nextElementSibling;
    const originalHTML = button.innerHTML;
    button.innerHTML = '<i class="bi bi-check"></i>';
    button.classList.add('btn-success');
    button.classList.remove('btn-outline-secondary');
    
    setTimeout(() => {
        button.innerHTML = originalHTML;
        button.classList.remove('btn-success');
        button.classList.add('btn-outline-secondary');
    }, 2000);
}
</script>
@endsection