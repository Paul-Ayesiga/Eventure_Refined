<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->name('home');




// admin routes
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    // Check if user has admin role
    Route::middleware('role:admin')->group(function () {
    Route::redirect('settings', 'settings-admin/profile');

    Route::view('settings/profile', 'admin.settings.profile')->name('admin.settings.profile');
    Route::view('settings/password', 'admin.settings.password')->name('admin.settings.password');
    Route::view('settings/appearance', 'admin.settings.appearance')->name('admin.settings.appearance');

    // Platform routes
    Route::view('dashboard', 'admin.dashboard')->name('admin-dashboard');
    });
});


// event organisation routes
Route::prefix('org')->middleware(['auth', 'verified', 'check.organiser'])->group(function () {
    Route::redirect('settings', 'settings-org/profile');

    Route::get('/{organisationId}/settings/profile', function($organisationId) {
        return view('organisation.settings.profile', ['organisationId' => $organisationId]);
    })->name('org.settings.profile');
    Route::view('/{organisationId}/settings/password', function($organisationId) {
        return view('organisation.settings.password', ['organisationId' => $organisationId]);
    })->name('org.settings.password');
    Route::view('/{organisationId}/settings/appearance', function($organisationId) {
        return view('organisation.settings.appearance', ['organisationId' => $organisationId]);
    })->name('org.settings.appearance');

    // Platform routes
    Route::get('/{organisationId}/dashboard', \App\Livewire\Org\Dashboard::class)->name('organisation-dashboard');
    Route::get('/{organisationId}/events', function($organisationId) {
        return view('organisation.events', ['organisationId' => $organisationId]);
    })->name('events');
    Route::view('reports', 'organisation.reports')->name('reports');
    Route::view('my-team', 'organisation.my-team')->name('my-team');
    Route::view('contacts', 'organisation.contacts')->name('contacts');
    Route::view('organisation-profile', 'organisation.organisation-profile')->name('organisation-profile');
    Route::view('coupons', 'organisation.coupons')->name('coupons');
    Route::view('tracking-codes', 'organisation.tracking-codes')->name('tracking-codes');
    Route::view('payment-collections', 'organisation.payment-collections')->name('payment-collections');
    Route::view('billing-details', 'organisation.billing-details')->name('billing-details');
    Route::view('subscription', 'organisation.subscription')->name('subscription');
    Route::get('merchandise', fn() => null)->name('merchandise');
    Route::get('help-support', fn() => null)->name('help-support');

    // Event routes
    Route::view('event/{id}/details', 'organisation.event-details')->name('event-details');
    Route::view('event/{id}/tickets', 'organisation.event-tickets')->name('event-tickets');
    Route::view('event/{id}/bookings', 'organisation.event-bookings')->name('event-bookings');
    Route::view('event/{id}/insights', 'organisation.event-insights')->name('event-insights');
    Route::view('event/{id}/settings', 'organisation.event-settings')->name('event-settings');
});



// event details routes for organiser to manage events
Route::middleware(['auth', 'verified', 'check.organiser'])->group(function () {
    Route::get('/events/{id}/details', \App\Livewire\Org\Events\EventDetails::class)->name('event-details');
    Route::get('/events/{id}/tickets', \App\Livewire\Org\Events\Tickets::class)->name('event-tickets');
    Route::get('/events/{id}/bookings', \App\Livewire\Org\Events\Bookings::class)->name('event-bookings');
    Route::get('/events/{id}/insights', \App\Livewire\Org\Events\Insights::class)->name('event-insights');
    Route::get('/events/{id}/waiting-list', \App\Livewire\Org\Events\WaitingList::class)->name('event-waiting-list');

    // Ticket generation routes
    Route::get('/bookings/{bookingId}/tickets', \App\Livewire\Org\Events\GenerateTicket::class)->name('events.bookings.tickets');
    Route::get('/attendees/{attendeeId}/ticket', \App\Livewire\Org\Events\GenerateTicket::class)->name('events.attendees.ticket');
});


// user routes
Route::prefix('usr')->middleware(['auth', 'verified'])->group(function () {
    Route::redirect('settings', 'settings-usr/profile');

    Route::view('settings/profile', 'user.settings.profile')->name('usr.settings.profile');
    Route::view('settings/password', 'user.settings.password')->name('usr.settings.password');
    Route::view('settings/appearance', 'user.settings.appearance')->name('usr.settings.appearance');

    // Platform routes
    Route::view('dashboard', 'user.dashboard')->name('user-dashboard');

    // Organization creation route
    Route::view('create-organisation', 'organisation.create')->name('create-organisation');
});


// Public ticket routes
Route::get('/tickets/view', function() {
    $bookingId = request('bookingId');
    $attendeeId = request('attendeeId');

    if (!$bookingId && !$attendeeId) {
        abort(404, 'Ticket not found');
    }

    // Load the ticket data to ensure it exists
    if ($bookingId) {
        $booking = \App\Models\Booking::with(['attendees.ticket', 'event.organisation'])->find($bookingId);
        if (!$booking) {
            abort(404, 'Booking not found');
        }
    } elseif ($attendeeId) {
        $attendee = \App\Models\Attendee::with(['booking.event.organisation', 'ticket'])->find($attendeeId);
        if (!$attendee) {
            abort(404, 'Attendee not found');
        }
    }

    return view('tickets.view', [
        'bookingId' => $bookingId,
        'attendeeId' => $attendeeId
    ]);
})->name('tickets.view');


require __DIR__ . '/auth.php';
