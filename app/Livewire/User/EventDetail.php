<?php

namespace App\Livewire\User;

use App\Models\Event;
use App\Models\Ticket;
use Livewire\Component;
use Carbon\Carbon;

class EventDetail extends Component
{
    public $eventId;
    public $event;
    public $tickets = [];
    public $selectedTickets = [];
    public $selectedDate;
    public $availableDates = [];

    public function mount($id)
    {
        $this->eventId = $id;
        $this->loadEvent();
        $this->loadTickets();
        $this->setupDates();
    }

    public function loadEvent()
    {
        $this->event = Event::with(['location', 'organisation'])->findOrFail($this->eventId);
    }

    public function loadTickets()
    {
        $this->tickets = Ticket::where('event_id', $this->eventId)
            ->where('status', 'active')
            ->whereRaw('quantity_available > quantity_sold')
            ->where('sale_start_date', '<=', now()->format('Y-m-d H:i:s'))
            ->where('sale_end_date', '>=', now()->format('Y-m-d H:i:s'))
            ->get();

        // Initialize selected tickets array
        foreach ($this->tickets as $ticket) {
            $this->selectedTickets[$ticket->id] = 0;
        }
    }

    public function setupDates()
    {
        if ($this->event->event_repeat === 'Does not repeat') {
            // Single date event
            $this->availableDates = [
                $this->event->start_date => Carbon::parse($this->event->start_date)->format('D, M d, Y')
            ];
            $this->selectedDate = $this->event->start_date;
        } else {
            // Multi-date event
            $startDate = Carbon::parse($this->event->start_date);
            $endDate = Carbon::parse($this->event->end_date);

            $dates = [];
            $currentDate = $startDate->copy();

            while ($currentDate->lte($endDate)) {
                $dateKey = $currentDate->format('Y-m-d');
                $dates[$dateKey] = $currentDate->format('D, M d, Y');

                if ($this->event->event_repeat === 'Daily') {
                    $currentDate->addDay();
                } elseif ($this->event->event_repeat === 'Weekly') {
                    $currentDate->addWeek();
                } elseif ($this->event->event_repeat === 'Monthly') {
                    $currentDate->addMonth();
                }
            }

            $this->availableDates = $dates;
            $this->selectedDate = array_key_first($dates);
        }
    }

    public function incrementTicket($ticketId)
    {
        $ticket = collect($this->tickets)->firstWhere('id', $ticketId);
        $maxPerBooking = $ticket->max_tickets_per_booking;
        $remainingQuantity = $ticket->quantity_available - $ticket->quantity_sold;

        if ($this->selectedTickets[$ticketId] < min($maxPerBooking, $remainingQuantity)) {
            $this->selectedTickets[$ticketId]++;
        }
    }

    public function decrementTicket($ticketId)
    {
        if ($this->selectedTickets[$ticketId] > 0) {
            $this->selectedTickets[$ticketId]--;
        }
    }

    public function getTotalSelectedTickets()
    {
        return array_sum($this->selectedTickets);
    }

    public function getTotalPrice()
    {
        $total = 0;
        foreach ($this->selectedTickets as $ticketId => $quantity) {
            $ticket = collect($this->tickets)->firstWhere('id', $ticketId);
            if ($ticket && $quantity > 0) {
                $total += $ticket->price * $quantity;
            }
        }
        return $total;
    }

    public function proceedToBooking()
    {
        if ($this->getTotalSelectedTickets() === 0) {
            $this->dispatch('toast', 'Please select at least one ticket', 'error');
            return;
        }

        // Store selected tickets in session
        session([
            'booking' => [
                'event_id' => $this->eventId,
                'selected_date' => $this->selectedDate,
                'selected_tickets' => array_filter($this->selectedTickets, function($quantity) {
                    return $quantity > 0;
                })
            ]
        ]);

        return redirect()->route('user.event.book', $this->eventId);
    }

    public function render()
    {
        return view('livewire.user.event-detail', [
            'totalPrice' => $this->getTotalPrice(),
            'totalTickets' => $this->getTotalSelectedTickets()
        ]);
    }
}
