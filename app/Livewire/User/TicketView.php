<?php

namespace App\Livewire\User;

use App\Models\Booking;
use App\Models\Attendee;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TicketView extends Component
{
    public $bookingId;
    public $booking;
    public $attendees;
    public $event;
    public $showSuccessMessage = false;

    public function mount($bookingId = null)
    {
        $this->bookingId = $bookingId;

        if ($this->bookingId) {
            $this->loadBooking();
        }

        // Check if we have a success message in the session
        $this->showSuccessMessage = session()->has('success');
    }

    protected function loadBooking()
    {
        // Get the booking with related data
        $this->booking = Booking::with(['event', 'items.ticket', 'dates'])
            ->where('id', $this->bookingId)
            ->where('user_id', Auth::id()) // Ensure the booking belongs to the current user
            ->first();

        if (!$this->booking) {
            return redirect()->route('user.bookings')->with('error', 'Booking not found.');
        }

        $this->event = $this->booking->event;

        // Get attendees for this booking
        $this->attendees = Attendee::where('booking_id', $this->bookingId)->get();
    }

    public function downloadTickets()
    {
        // Redirect to the ticket download endpoint
        return redirect()->route('events.bookings.tickets', ['bookingId' => $this->bookingId]);
    }

    public function printTickets()
    {
        $this->dispatch('print-tickets');
    }

    public function shareTicket($attendeeId)
    {
        // Redirect to the individual ticket share page
        return redirect()->route('events.attendees.ticket', ['attendeeId' => $attendeeId]);
    }

    public function render()
    {
        return view('livewire.user.ticket-view');
    }
}
