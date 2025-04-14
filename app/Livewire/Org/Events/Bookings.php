<?php

namespace App\Livewire\Org\Events;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Attendee;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Bookings extends Component
{
    use WithPagination;

    public int $eventId;
    public $event;

    // For modal control
    public $isModalOpen = false;
    public $selectedBooking = null;

    // For simulation
    public $isSimulating = false;
    public $simulationCount = 1;
    public $selectedTicketId;
    public $ticketQuantity = 1;
    public $selectedCustomerId = null;
    public $customers = [];
    public $joinWaitingList = true;
    public $selectedEventDates = [];
    public $eventDates = [];
    public $selectedTicketRepeats = false;

    // For manual attendee information
    public $useCustomerAsAttendee = true;
    public $attendees = [];

    public function mount($id)
    {
        $this->eventId = $id;
        $this->event = Event::findOrFail($id);
        $this->loadCustomers();
        $this->initializeAttendees();
        $this->loadEventDates();

        // Check if user is authorized to manage this event
        // if ($this->event->organiser->organiser_id !== Auth::id()) {
        //     abort(403, 'Unauthorized action.');
        // }
    }

    /**
     * Initialize the attendees array with empty values
     */
    public function initializeAttendees()
    {
        $this->attendees = [
            [
                'first_name' => '',
                'last_name' => '',
                'email' => '',
                'phone' => ''
            ]
        ];
    }

    /**
     * Update the attendees array when ticket quantity changes
     */
    public function updatedTicketQuantity($value)
    {
        if ($value < 1) {
            return;
        }

        $currentCount = count($this->attendees);

        if ($value > $currentCount) {
            // Add more attendee forms
            for ($i = $currentCount; $i < $value; $i++) {
                $this->attendees[] = [
                    'first_name' => '',
                    'last_name' => '',
                    'email' => '',
                    'phone' => ''
                ];
            }
        } elseif ($value < $currentCount) {
            // Remove excess attendee forms
            $this->attendees = array_slice($this->attendees, 0, $value);
        }
    }

    public function loadCustomers()
    {
        $this->customers = \App\Models\User::role('user')
            ->with('userDetail')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->userDetail?->phone_number,
                ];
            });
    }

    /**
     * Load event dates based on event repeat settings
     */
    public function loadEventDates()
    {
        $this->eventDates = [];

        // Add the main event date
        $startDate = Carbon::parse($this->event->start_date);
        $this->eventDates[$startDate->format('Y-m-d')] = $startDate->format('M d, Y');

        // If event repeats, add additional dates
        if ($this->event->event_repeat !== 'Does not repeat') {
            // If end_date is set, use it to calculate the date range
            if ($this->event->end_date) {
                $endDate = Carbon::parse($this->event->end_date);
                $currentDate = $startDate->copy()->addDay();

                // Add all dates between start_date and end_date
                while ($currentDate->lte($endDate)) {
                    $this->eventDates[$currentDate->format('Y-m-d')] = $currentDate->format('M d, Y');
                    $currentDate->addDay();
                }
            }
            // Fallback to repeat_days if end_date is not set
            elseif ($this->event->repeat_days > 0) {
                $repeatDays = $this->event->repeat_days;

                for ($i = 1; $i <= $repeatDays; $i++) {
                    $nextDate = $startDate->copy()->addDays($i);
                    $this->eventDates[$nextDate->format('Y-m-d')] = $nextDate->format('M d, Y');
                }
            }
        }

        // Initialize selected dates array
        $this->selectedEventDates = [];
    }

    /**
     * Update selected ticket repeats status when ticket is selected
     */
    public function updatedSelectedTicketId($value)
    {
        if (!$value) {
            $this->selectedTicketRepeats = false;
            return;
        }

        $ticket = Ticket::find($value);
        if ($ticket) {
            $this->selectedTicketRepeats = $ticket->repeat_ticket;
        }
    }

    public function viewBooking($bookingId)
    {
        $this->selectedBooking = Booking::with(['tickets', 'attendees'])->findOrFail($bookingId);
        $this->isModalOpen = true;
    }

    public function printBooking($bookingId)
    {
        // Implement print functionality
        $this->dispatch('print-booking', bookingId: $bookingId);
    }

    public function generateTickets($bookingId)
    {
        // Redirect to the ticket generation page for all attendees in a booking
        return $this->redirect(route('events.bookings.tickets', ['bookingId' => $bookingId]), navigate: true);
    }

    public function generateTicket($attendeeId)
    {
        // Redirect to the ticket generation page for a single attendee
        return $this->redirect(route('events.attendees.ticket', ['attendeeId' => $attendeeId]), navigate: true);
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->selectedBooking = null;
    }

    public function simulatePurchase()
    {
        // First validate basic requirements
        $validationRules = [
            'selectedCustomerId' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    $customer = \App\Models\User::find($value);
                    if (!$customer || !$customer->hasRole('user')) {
                        $fail('Selected customer is not valid.');
                    }
                }
            ],
            'selectedTicketId' => [
                'required',
                'exists:tickets,id',
                function ($attribute, $value, $fail) {
                    $ticket = Ticket::find($value);

                    if (!$ticket || $ticket->event_id !== $this->eventId) {
                        $fail('Selected ticket is not valid for this event.');
                    }
                    if ($ticket->status !== 'active') {
                        $fail('Selected ticket is not active.');
                    }
                }
            ],
            'ticketQuantity' => [
                'required',
                'integer',
                'min:1'
            ]
        ];

        // Get the ticket to check if it's a repeating ticket
        $ticket = Ticket::find($this->selectedTicketId);

        // Add validation for event dates if the ticket is not a repeating ticket and event has multiple dates
        if ($ticket && !$ticket->repeat_ticket && count($this->eventDates) > 1) {
            $validationRules['selectedEventDates'] = [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) {
                    if (empty($value)) {
                        $fail('Please select at least one event date.');
                    }

                    // Validate that all selected dates are valid event dates
                    foreach ($value as $date) {
                        if (!array_key_exists($date, $this->eventDates)) {
                            $fail('One or more selected dates are invalid.');
                            break;
                        }
                    }
                }
            ];
        }

        // Add validation rules for manual attendee information if not using customer as attendee
        if (!$this->useCustomerAsAttendee) {
            // Make sure we have the right number of attendees
            if (count($this->attendees) != $this->ticketQuantity) {
                $this->updatedTicketQuantity($this->ticketQuantity);
            }

            // Validate each attendee's information
            foreach (range(0, $this->ticketQuantity - 1) as $index) {
                $validationRules["attendees.{$index}.first_name"] = 'required|string|max:255';
                $validationRules["attendees.{$index}.last_name"] = 'required|string|max:255';
                $validationRules["attendees.{$index}.email"] = 'required|email|max:255';
                $validationRules["attendees.{$index}.phone"] = 'nullable|string|max:20';
            }
        }

        $this->validate($validationRules);

        // Get the ticket after basic validation passes
        $ticket = Ticket::find($this->selectedTicketId);
        if (!$ticket) {
            $this->addError('selectedTicketId', 'Selected ticket is not valid.');
            return;
        }

        $customer = \App\Models\User::findOrFail($this->selectedCustomerId);

        // Check if ticket is available for sale
        if (!now()->between($ticket->sale_start_date, $ticket->sale_end_date)) {
            if ($this->joinWaitingList) {
                // Check if user is already on the waiting list
                $existingEntry = $ticket->waitingList()
                    ->where('user_id', $customer->id)
                    ->where('status', 'pending')
                    ->first();

                if ($existingEntry) {
                    $this->dispatch('toast', "You are already on the waiting list for {$ticket->name}. Your requested quantity has been updated.", 'info', 'top-right');
                } else {
                    $this->dispatch('toast', "You have been added to the waiting list for {$ticket->name}.", 'info', 'top-right');
                }
                // Add to waiting list if not available
                $ticket->addToWaitingList($customer, $this->ticketQuantity);
            } else {
                $this->dispatch('toast', "Ticket is not available for sale at this time.", 'error', 'top-right');
            }
            $this->reset(['selectedCustomerId', 'selectedTicketId', 'ticketQuantity', 'isSimulating']);
            return;
        }

        // Validate ticket-specific rules
        $this->validate([
            'ticketQuantity' => [
                function ($attribute, $value, $fail) use ($ticket, $customer) {
                    if ($ticket->max_tickets_per_booking && $value > $ticket->max_tickets_per_booking) {
                        $fail("Cannot book more than {$ticket->max_tickets_per_booking} tickets per booking.");
                    }
                    if ($ticket->quantity_available < $value) {
                        if ($this->joinWaitingList) {
                            // Check if user is already on the waiting list
                            $existingEntry = $ticket->waitingList()
                                ->where('user_id', $customer->id)
                                ->where('status', 'pending')
                                ->first();

                            if ($existingEntry) {
                                $fail("You are already on the waiting list for this ticket. Your requested quantity has been updated.");
                            } else {
                                $fail("Not enough tickets available. You have been added to the waiting list.");
                            }
                            // Add to waiting list if not enough tickets
                            $ticket->addToWaitingList($customer, $value);
                        } else {
                            $fail("Not enough tickets available. Only {$ticket->quantity_available} tickets remaining.");
                        }
                    }
                }
            ]
        ]);

        // Create booking data array
        $bookingData = [
            'event_id' => $this->eventId,
            'user_id' => $customer->id,
            'booking_reference' => 'TEST-' . Str::random(8),
            'status' => 'confirmed',
            'total_amount' => $ticket->price * $this->ticketQuantity,
            'payment_status' => 'paid'
        ];

        // Create booking
        $booking = Booking::create($bookingData);

        // Add selected dates if the ticket is not a repeating ticket
        if (!$ticket->repeat_ticket && count($this->eventDates) > 1 && !empty($this->selectedEventDates)) {
            foreach ($this->selectedEventDates as $date) {
                $booking->dates()->create([
                    'event_date' => $date
                ]);
            }
        } else if ($ticket->repeat_ticket && count($this->eventDates) > 1) {
            // If ticket repeats, add all event dates
            foreach ($this->eventDates as $dateValue => $dateLabel) {
                $booking->dates()->create([
                    'event_date' => $dateValue
                ]);
            }
        } else {
            // If event doesn't repeat, add the main event date
            $booking->dates()->create([
                'event_date' => array_key_first($this->eventDates)
            ]);
        }



        // Create booking item
        $booking->tickets()->attach($ticket->id, [
            'quantity' => $this->ticketQuantity,
            'unit_price' => $ticket->price,
            'subtotal' => $ticket->price * $this->ticketQuantity
        ]);

        // Create attendee for each ticket
        for ($i = 0; $i < $this->ticketQuantity; $i++) {
            // Use either customer info or manual attendee info based on selection
            if ($this->useCustomerAsAttendee) {
                Attendee::create([
                    'booking_id' => $booking->id,
                    'ticket_id' => $ticket->id,
                    'first_name' => $customer->name,
                    'last_name' => '',
                    'email' => $customer->email,
                    'phone' => $customer->userDetail?->phone_number
                ]);
            } else {
                // Use the specific attendee information from the array
                $attendeeData = $this->attendees[$i];
                Attendee::create([
                    'booking_id' => $booking->id,
                    'ticket_id' => $ticket->id,
                    'first_name' => $attendeeData['first_name'],
                    'last_name' => $attendeeData['last_name'],
                    'email' => $attendeeData['email'],
                    'phone' => $attendeeData['phone']
                ]);
            }
        }

        // Update ticket quantities
        $ticket->increment('quantity_sold', $this->ticketQuantity);

        // Reset form
        $this->reset([
            'selectedCustomerId',
            'selectedTicketId',
            'ticketQuantity',
            'isSimulating',
            'useCustomerAsAttendee',
            'selectedEventDates',
            'selectedTicketRepeats'
        ]);

        // Reset attendees array
        $this->initializeAttendees();

        $this->dispatch('toast', "Successfully created booking for {$customer->name}.", 'success', 'top-right');
        $this->dispatch('booking-changed');
    }

    public function deleteBooking($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        // Check if booking is already cancelled
        if ($booking->status === 'cancelled') {
            $this->dispatch('toast', 'Booking is already cancelled.', 'error', 'top-right');
            return;
        }

        // Update ticket quantities before deleting
        foreach ($booking->tickets as $ticket) {
            $ticket->decrement('quantity_sold', $ticket->pivot->quantity);
        }

        // Delete the booking (this will cascade delete attendees and booking items)
        $booking->delete();

        $this->dispatch('booking-changed');
        $this->dispatch('toast', 'Booking deleted successfully.', 'success', 'top-right');
    }

    public function render()
    {
        $bookings = $this->event->bookings()
            ->with([
                'tickets' => function ($query) {
                    $query->withPivot('quantity', 'unit_price', 'subtotal');
                },
                'attendees' => function ($query) {
                    $query->with('ticket');
                }
            ])
            ->latest()
            ->paginate(10);

        return view('livewire.org.events.bookings', [
            'bookings' => $bookings,
            'tickets' => $this->event->tickets()->where('status', 'active')->get()
        ])->layout('components.layouts.event-detail', [
            'eventId' => $this->eventId,
            'event' => $this->event
        ]);
    }
}
