@if($guestList->count() > 0)
<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Responded At</th>
                <th>Invitation Link</th>
            </tr>
        </thead>
        <tbody>
            @foreach($guestList as $guest)
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
                        {{ $guest->rsvp_confirmed_at->format('M d, Y g:i A') }}
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" 
                               value="{{ $guest->invitation_url }}" 
                               id="link-tab-{{ $guest->id }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" 
                                onclick="copyToClipboard('link-tab-{{ $guest->id }}')" title="Copy Link">
                            <i class="bi bi-copy"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="text-center py-4">
    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
    <p class="text-muted mt-2">No guests in this category</p>
</div>
@endif