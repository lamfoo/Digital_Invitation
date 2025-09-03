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

        return view('rsvp.invitation-simple-fix', compact('guest'));
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
                ->with('error', 'Este convite expirou.');
        }

        // Check if already responded
        if ($guest->hasResponded()) {
            return redirect()->route('rsvp.show', $token)
                ->with('error', 'Você já respondeu a este convite.');
        }

        // Debug: Check what we received
        $rsvpStatus = $request->input('rsvp_status');
        
        if (empty($rsvpStatus)) {
            return redirect()->route('rsvp.show', $token)
                ->with('error', 'Erro: Status do RSVP não foi recebido. Dados recebidos: ' . json_encode($request->all()));
        }

        if (!in_array($rsvpStatus, ['yes', 'no', 'maybe'])) {
            return redirect()->route('rsvp.show', $token)
                ->with('error', 'Erro: Status inválido recebido: ' . $rsvpStatus);
        }

        // Update guest response directly
        $guest->rsvp_status = $rsvpStatus;
        $guest->rsvp_confirmed_at = now();
        $success = $guest->save();

        if (!$success) {
            return redirect()->route('rsvp.show', $token)
                ->with('error', 'Erro ao salvar resposta. Tente novamente.');
        }

        return redirect()->route('rsvp.show', $token)
            ->with('success', 'Obrigado pela sua resposta!');
    }
}
