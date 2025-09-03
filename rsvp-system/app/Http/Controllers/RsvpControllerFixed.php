<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RsvpControllerFixed extends Controller
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

        return view('rsvp.invitation-working', compact('guest'));
    }

    /**
     * Submit RSVP response.
     */
    public function submitRsvp(Request $request, string $token): RedirectResponse
    {
        $guest = Guest::byToken($token)->with('event')->first();

        if (!$guest) {
            abort(404, 'Invitation not found.');
        }

        // Check if invitation has expired
        if (!$guest->isInvitationValid()) {
            return redirect()->route('rsvp.show', $token)
                ->with('error', 'This invitation has expired.');
        }

        // Check if already responded
        if ($guest->hasResponded()) {
            return redirect()->route('rsvp.show', $token)
                ->with('error', 'You have already responded to this invitation.');
        }

        // Validate input
        $request->validate([
            'rsvp_status' => 'required|in:yes,no,maybe',
        ]);

        // Update guest response directly
        $guest->rsvp_status = $request->input('rsvp_status');
        $guest->rsvp_confirmed_at = now();
        $guest->save();

        return redirect()->route('rsvp.show', $token)
            ->with('success', 'Thank you for your response!');
    }
}