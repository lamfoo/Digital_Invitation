<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RsvpController extends Controller
{
    /**
     * Display the invitation page for a guest.
     */
    public function show(string $token): View
    {
        $guest = Guest::byToken($token)->with('event')->first();

        if (!$guest) {
            abort(404, 'Invitation not found.');
        }

        // Check if invitation has expired
        if (!$guest->isInvitationValid()) {
            return view('rsvp.expired', compact('guest'));
        }

        // Check if already responded
        if ($guest->hasResponded()) {
            return view('rsvp.already-responded', compact('guest'));
        }

        return view('rsvp.invitation-debug', compact('guest'));
    }

    /**
     * Submit RSVP response.
     */
    public function submitRsvp(Request $request, string $token): RedirectResponse
    {
        // Debug logging
        \Log::info('RSVP Submission Attempt', [
            'token' => $token,
            'request_data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url()
        ]);

        $guest = Guest::byToken($token)->with('event')->first();

        if (!$guest) {
            \Log::error('Guest not found for token: ' . $token);
            abort(404, 'Invitation not found.');
        }

        \Log::info('Guest found', ['guest_id' => $guest->id, 'name' => $guest->name]);

        // Check if invitation has expired
        if (!$guest->isInvitationValid()) {
            \Log::warning('Invitation expired', ['guest_id' => $guest->id, 'expiration' => $guest->event->rsvp_expiration_at]);
            return redirect()->route('rsvp.show', $token)
                ->with('error', 'This invitation has expired.');
        }

        // Check if already responded
        if ($guest->hasResponded()) {
            \Log::warning('Guest already responded', ['guest_id' => $guest->id, 'status' => $guest->rsvp_status]);
            return redirect()->route('rsvp.show', $token)
                ->with('error', 'You have already responded to this invitation.');
        }

        try {
            $validated = $request->validate([
                'rsvp_status' => 'required|in:yes,no,maybe',
            ]);

            \Log::info('Validation passed', ['validated_data' => $validated]);

            $guest->update([
                'rsvp_status' => $validated['rsvp_status'],
                'rsvp_confirmed_at' => now(),
            ]);

            \Log::info('RSVP updated successfully', [
                'guest_id' => $guest->id,
                'new_status' => $validated['rsvp_status'],
                'confirmed_at' => $guest->rsvp_confirmed_at
            ]);

            return redirect()->route('rsvp.show', $token)
                ->with('success', 'Thank you for your response!');

        } catch (\Exception $e) {
            \Log::error('RSVP submission error', [
                'error' => $e->getMessage(),
                'guest_id' => $guest->id ?? null,
                'token' => $token
            ]);
            
            return redirect()->route('rsvp.show', $token)
                ->with('error', 'There was an error processing your response. Please try again.');
        }
    }
}
