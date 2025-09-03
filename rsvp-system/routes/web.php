<?php

use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\GuestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RsvpController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('admin.events.index');
});

// Public RSVP routes
Route::get('/invite/{token}', [RsvpController::class, 'show'])->name('rsvp.show');
Route::post('/invite/{token}/rsvp', [RsvpController::class, 'submitRsvp'])->name('rsvp.submit');
Route::get('/invite/{token}/rsvp', [RsvpController::class, 'submitRsvp'])->name('rsvp.submit.get'); // Debug route

Route::get('/dashboard', function () {
    return redirect()->route('admin.events.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes (protected by auth middleware)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    // Events
    Route::resource('events', EventController::class);
    
    // Guests for specific events
    Route::prefix('events/{event}')->name('events.')->group(function () {
        Route::get('/guests', [GuestController::class, 'index'])->name('guests.index');
        Route::post('/guests', [GuestController::class, 'store'])->name('guests.store');
        Route::put('/guests/{guest}', [GuestController::class, 'update'])->name('guests.update');
        Route::delete('/guests/{guest}', [GuestController::class, 'destroy'])->name('guests.destroy');
        Route::post('/guests/import', [GuestController::class, 'import'])->name('guests.import');
        
        // RSVP management
        Route::get('/rsvps', [GuestController::class, 'rsvps'])->name('rsvps');
        Route::get('/rsvps/export', [GuestController::class, 'exportCsv'])->name('rsvps.export');
    });
});

require __DIR__.'/auth.php';
