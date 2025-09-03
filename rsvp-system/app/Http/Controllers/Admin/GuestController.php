<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;
use League\Csv\Reader;
use League\Csv\Writer;

class GuestController extends Controller
{
    /**
     * Display guests for a specific event.
     */
    public function index(Event $event): View
    {
        $guests = $event->guests()->orderBy('name')->paginate(15);
        
        return view('admin.guests.index', compact('event', 'guests'));
    }

    /**
     * Store a newly created guest.
     */
    public function store(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['event_id'] = $event->id;
        Guest::create($validated);

        return redirect()->route('admin.events.guests.index', $event)
            ->with('success', 'Guest added successfully!');
    }

    /**
     * Update the specified guest.
     */
    public function update(Request $request, Event $event, Guest $guest): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $guest->update($validated);

        return redirect()->route('admin.events.guests.index', $event)
            ->with('success', 'Guest updated successfully!');
    }

    /**
     * Remove the specified guest.
     */
    public function destroy(Event $event, Guest $guest): RedirectResponse
    {
        $guest->delete();

        return redirect()->route('admin.events.guests.index', $event)
            ->with('success', 'Guest removed successfully!');
    }

    /**
     * Import guests from CSV file.
     */
    public function import(Request $request, Event $event): RedirectResponse
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        try {
            $csv = Reader::createFromPath($request->file('csv_file')->getRealPath(), 'r');
            $csv->setHeaderOffset(0);
            
            $records = $csv->getRecords();
            $imported = 0;

            foreach ($records as $record) {
                if (!empty($record['name']) || !empty($record['Name'])) {
                    $name = $record['name'] ?? $record['Name'];
                    
                    Guest::create([
                        'event_id' => $event->id,
                        'name' => trim($name),
                    ]);
                    
                    $imported++;
                }
            }

            return redirect()->route('admin.events.guests.index', $event)
                ->with('success', "Successfully imported {$imported} guests!");

        } catch (\Exception $e) {
            return redirect()->route('admin.events.guests.index', $event)
                ->with('error', 'Error importing CSV: ' . $e->getMessage());
        }
    }

    /**
     * Show RSVP report for an event.
     */
    public function rsvps(Event $event): View
    {
        $guests = $event->guests()
            ->orderBy('rsvp_status')
            ->orderBy('name')
            ->get();

        $stats = [
            'total' => $guests->count(),
            'confirmed' => $guests->where('rsvp_status', 'yes')->count(),
            'declined' => $guests->where('rsvp_status', 'no')->count(),
            'maybe' => $guests->where('rsvp_status', 'maybe')->count(),
            'pending' => $guests->where('rsvp_status', 'pending')->count(),
        ];

        return view('admin.guests.rsvps', compact('event', 'guests', 'stats'));
    }

    /**
     * Export RSVP list as CSV.
     */
    public function exportCsv(Event $event): Response
    {
        $guests = $event->guests()->orderBy('name')->get();

        $csv = Writer::createFromString('');
        $csv->insertOne(['Name', 'RSVP Status', 'Confirmed At']);

        foreach ($guests as $guest) {
            $csv->insertOne([
                $guest->name,
                ucfirst($guest->rsvp_status),
                $guest->rsvp_confirmed_at ? $guest->rsvp_confirmed_at->format('Y-m-d H:i:s') : '',
            ]);
        }

        $filename = 'rsvp-' . $event->title . '-' . now()->format('Y-m-d') . '.csv';

        return response($csv->toString())
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
