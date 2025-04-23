<?php

namespace App\Livewire\User;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Attendee;
use App\Models\BookingDate;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingProcess extends Component
{
    public $eventId;
    public $event;
    public $selectedDate;
    public $selectedTickets = [];
    public $tickets = [];

    // Attendee information
    public $attendees = [];

    // Payment information
    public $paymentMethod = 'credit_card';
    public $cardNumber;
    public $cardExpiry;
    public $cardCvc;
    public $billingName;
    public $billingEmail;
    public $billingPhone;

    // Steps
    public $currentStep = 1;
    public $totalSteps = 3;

    // Form validation rules
    protected function rules()
    {
        $rules = [
            'selectedDate' => 'required|date',
            'attendees' => 'required|array|min:1',
            'attendees.*.first_name' => 'required|string|max:255',
            'attendees.*.last_name' => 'required|string|max:255',
            'attendees.*.email' => 'required|email|max:255',
            'attendees.*.phone' => 'nullable|string|max:20',
        ];

        if ($this->currentStep === 3) {
            $rules = array_merge($rules, [
                'paymentMethod' => 'required|in:credit_card,paypal',
                'billingName' => 'required|string|max:255',
                'billingEmail' => 'required|email|max:255',
                'billingPhone' => 'required|string|max:20',
            ]);

            if ($this->paymentMethod === 'credit_card') {
                $rules = array_merge($rules, [
                    'cardNumber' => 'required|string|min:16|max:19',
                    'cardExpiry' => 'required|string|size:5',
                    'cardCvc' => 'required|string|size:3',
                ]);
            }
        }

        return $rules;
    }

    public function mount($id)
    {
        $this->eventId = $id;
        $this->loadEvent();

        // Check if we have booking data in session
        if (session()->has('booking')) {
            $bookingData = session('booking');
            $this->selectedDate = $bookingData['selected_date'];
            $this->selectedTickets = $bookingData['selected_tickets'];

            // Load tickets
            $this->loadTickets();

            // Initialize attendees array based on selected tickets
            $this->initializeAttendees();
        } else {
            // Redirect back to event detail if no booking data
            return redirect()->route('user.event.detail', $this->eventId);
        }
    }

    public function loadEvent()
    {
        $this->event = Event::with(['location', 'organisation'])->findOrFail($this->eventId);
    }

    public function loadTickets()
    {
        $ticketIds = array_keys($this->selectedTickets);
        $this->tickets = Ticket::whereIn('id', $ticketIds)->get();
    }

    public function initializeAttendees()
    {
        $this->attendees = [];
        $attendeeIndex = 0;

        foreach ($this->selectedTickets as $ticketId => $quantity) {
            $ticket = collect($this->tickets)->firstWhere('id', $ticketId);

            for ($i = 0; $i < $quantity; $i++) {
                // For the first attendee, use the authenticated user's information if available
                if ($attendeeIndex === 0 && Auth::check()) {
                    $user = Auth::user();
                    $names = explode(' ', $user->name, 2);
                    $firstName = $names[0];
                    $lastName = isset($names[1]) ? $names[1] : '';

                    $this->attendees[] = [
                        'ticket_id' => $ticketId,
                        'ticket_name' => $ticket->name,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $user->email,
                        'phone' => '',
                    ];
                } else {
                    $this->attendees[] = [
                        'ticket_id' => $ticketId,
                        'ticket_name' => $ticket->name,
                        'first_name' => '',
                        'last_name' => '',
                        'email' => '',
                        'phone' => '',
                    ];
                }

                $attendeeIndex++;
            }
        }

        // Initialize billing information with the first attendee's info
        if (!empty($this->attendees)) {
            $this->billingName = $this->attendees[0]['first_name'] . ' ' . $this->attendees[0]['last_name'];
            $this->billingEmail = $this->attendees[0]['email'];
            $this->billingPhone = $this->attendees[0]['phone'];
        }
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

    public function getTotalTickets()
    {
        return array_sum($this->selectedTickets);
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'attendees.*.first_name' => 'required|string|max:255',
                'attendees.*.last_name' => 'required|string|max:255',
                'attendees.*.email' => 'required|email|max:255',
            ]);
        } elseif ($this->currentStep === 2) {
            // Validation for payment information will be done in the completeBooking method
        }

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function prevStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function completeBooking()
    {
        // Validate payment information
        $this->validate();

        try {
            DB::beginTransaction();

            // Create booking
            $booking = new Booking([
                'event_id' => $this->eventId,
                'user_id' => Auth::id(),
                'booking_reference' => 'BK' . strtoupper(uniqid()),
                'status' => 'confirmed', // In a real app, this would be 'pending' until payment is confirmed
                'total_amount' => $this->getTotalPrice(),
                'payment_status' => 'paid', // In a real app, this would be handled by the payment gateway
            ]);

            $booking->save();

            // Create booking date
            BookingDate::create([
                'booking_id' => $booking->id,
                'event_date' => $this->selectedDate,
            ]);

            // Create booking items
            foreach ($this->selectedTickets as $ticketId => $quantity) {
                if ($quantity > 0) {
                    $ticket = collect($this->tickets)->firstWhere('id', $ticketId);

                    BookingItem::create([
                        'booking_id' => $booking->id,
                        'ticket_id' => $ticketId,
                        'quantity' => $quantity,
                        'unit_price' => $ticket->price,
                        'subtotal' => $ticket->price * $quantity,
                    ]);

                    // Update ticket quantity sold
                    $ticket->increment('quantity_sold', $quantity);
                }
            }

            // Create attendees
            foreach ($this->attendees as $attendeeData) {
                Attendee::create([
                    'booking_id' => $booking->id,
                    'ticket_id' => $attendeeData['ticket_id'],
                    'first_name' => $attendeeData['first_name'],
                    'last_name' => $attendeeData['last_name'],
                    'email' => $attendeeData['email'],
                    'phone' => $attendeeData['phone'],
                    'check_in_status' => false,
                ]);
            }

            DB::commit();

            // Clear booking session data
            session()->forget('booking');

            // Redirect to booking confirmation page
            return redirect()->route('tickets.view', ['bookingId' => $booking->id])->with('success', 'Booking completed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('toast', 'An error occurred while processing your booking. Please try again.', 'error');
        }
    }

    public function render()
    {
        return view('livewire.user.booking-process', [
            'totalPrice' => $this->getTotalPrice(),
            'totalTickets' => $this->getTotalTickets(),
        ]);
    }
}
