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
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

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

    // For Stripe payment
    public $clientSecret;

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

        // Check if event is archived
        if ($this->event->isArchived()) {
            session()->flash('error', 'This event has been archived and is no longer available for booking.');
            return redirect()->route('user.event.detail', $this->eventId);
        }

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
        // Check if event is archived
        if ($this->event->isArchived()) {
            $this->dispatch('toast', 'This event has been archived and is no longer available for booking.', 'error');
            return redirect()->route('user.event.detail', $this->eventId);
        }

        if ($this->currentStep === 1) {
            $this->validate([
                'attendees.*.first_name' => 'required|string|max:255',
                'attendees.*.last_name' => 'required|string|max:255',
                'attendees.*.email' => 'required|email|max:255',
            ]);
        } elseif ($this->currentStep === 2) {
            // Check if user is on waiting list before proceeding to payment step
            if ($this->isOnWaitingList()) {
                $this->dispatch('toast', 'Payment cannot be processed for tickets on waiting list.', 'error');
                return;
            }
            // Validation for payment information will be done in the completeBooking method
        }

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;

            // Notify the frontend that the step has changed
            $this->dispatch('stepChanged', $this->currentStep);

            // If moving to payment step and credit card is selected, create payment intent
            if ($this->currentStep === 3 && $this->paymentMethod === 'credit_card') {
                $this->createPaymentIntent();
            }
        }
    }

    /**
     * Create a Stripe payment intent and emit the client secret
     */
    public function createPaymentIntent()
    {
        try {
            // Check if Stripe is configured
            $stripeKey = config('cashier.key');
            $stripeSecret = config('cashier.secret');

            if (!$stripeKey || !$stripeSecret) {
                Log::warning('Stripe keys not configured. STRIPE_KEY and STRIPE_SECRET must be set in .env');
                $this->dispatch('toast', 'Payment processing is not configured. Please contact support.', 'error');

                // Dispatch an event to notify the frontend that Stripe is not configured
                $this->dispatch('stripe-configuration-error', [
                    'message' => 'Stripe API keys are missing. Please check your configuration.'
                ]);
                return;
            }

            // Set up Stripe API key
            Stripe::setApiKey($stripeSecret);

            // Calculate amount in cents/smallest currency unit
            $amount = (int)($this->getTotalPrice() * 100);

            // Make sure we have a valid amount
            if ($amount <= 0) {
                $this->dispatch('toast', 'Invalid payment amount.', 'error');
                return;
            }

            // Get currency from event or use default
            $currency = $this->event->currency ? strtolower($this->event->currency) : 'usd';

            // Create a payment intent
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => $currency,
                'metadata' => [
                    'event_id' => $this->eventId,
                    'user_id' => Auth::id(),
                ],
                'description' => 'Booking for ' . $this->event->name,
            ]);

            Log::info('Payment intent created successfully', [
                'payment_intent_id' => $paymentIntent->id,
                'amount' => $amount,
                'currency' => $currency
            ]);

            // Store the client secret for debugging
            $clientSecret = $paymentIntent->client_secret;

            // Log the client secret type and format for debugging
            Log::info('Client secret type and format', [
                'type' => gettype($clientSecret),
                'starts_with_pi' => str_starts_with($clientSecret, 'pi_'),
                'length' => strlen($clientSecret)
            ]);

            // Store the client secret in a public property
            $this->clientSecret = $clientSecret;

            // Emit an event to notify the frontend that the payment intent is ready
            $this->dispatch('payment-intent-ready');

        } catch (ApiErrorException $e) {
            // Handle specific Stripe API errors
            Log::error('Stripe API error: ' . $e->getMessage(), [
                'error_type' => $e->getStripeCode(),
                'error_code' => $e->getHttpStatus(),
                'trace' => $e->getTraceAsString()
            ]);

            $errorMessage = 'Stripe payment error: ' . $e->getMessage();
            $this->dispatch('toast', $errorMessage, 'error');

            // Dispatch an event to notify the frontend of the error
            $this->dispatch('stripe-configuration-error', [
                'message' => $errorMessage
            ]);
        } catch (\Exception $e) {
            // Handle general errors
            Log::error('Stripe payment intent creation error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);

            $errorMessage = 'Error creating payment: ' . $e->getMessage();
            $this->dispatch('toast', $errorMessage, 'error');

            // Dispatch an event to notify the frontend of the error
            $this->dispatch('stripe-configuration-error', [
                'message' => $errorMessage
            ]);
        }
    }

    /**
     * Handle successful payment
     */
    public function handlePaymentSuccess()
    {
        try {
            // Process the booking after successful payment
            $booking = $this->completeBooking();

            if ($booking) {
                // Store the booking ID in the session for redirect
                session()->put('completed_booking_id', $booking->id);

                // Use JavaScript to redirect to the tickets page
                $this->dispatch('redirect-to', ['url' => route('tickets.view', ['bookingId' => $booking->id])]);

                // Return the booking ID for the JavaScript callback
                return ['bookingId' => $booking->id, 'redirect' => route('tickets.view', ['bookingId' => $booking->id])];
            }
        } catch (\Exception $e) {
            Log::error('Error handling payment success: ' . $e->getMessage());
            $this->dispatch('toast', 'An error occurred while processing your booking. Please try again.', 'error');
        }
    }

    /**
     * Check if there's a completed booking to redirect to
     */
    public function checkCompletedBooking()
    {
        if (session()->has('completed_booking_id')) {
            $bookingId = session()->get('completed_booking_id');
            session()->forget('completed_booking_id');

            // Return the redirect URL
            return ['redirect' => route('tickets.view', ['bookingId' => $bookingId])];
        }

        return null;
    }

    /**
     * Handle payment method changes
     */
    public function updatedPaymentMethod($value)
    {
        if ($value === 'credit_card' && $this->currentStep === 3) {
            // Initialize Stripe payment when switching to credit card
            $this->createPaymentIntent();
        }
    }

    public function prevStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    /**
     * Check if the current user is on the waiting list for any of the selected tickets
     */
    private function isOnWaitingList()
    {
        $userId = Auth::id();

        foreach ($this->selectedTickets as $ticketId => $quantity) {
            if ($quantity > 0) {
                $ticket = collect($this->tickets)->firstWhere('id', $ticketId);

                // Check if user is on waiting list for this ticket
                $onWaitingList = $ticket->waitingList()
                    ->where('user_id', $userId)
                    ->whereIn('status', ['pending', 'notified'])
                    ->exists();

                if ($onWaitingList) {
                    return true;
                }
            }
        }

        return false;
    }

    public function completeBooking()
    {
        // Check if event is archived
        if ($this->event->isArchived()) {
            $this->dispatch('toast', 'This event has been archived and is no longer available for booking.', 'error');
            return redirect()->route('user.event.detail', $this->eventId);
        }

        // Validate payment information
        $this->validate();

        // Check if user is on waiting list for any selected tickets
        if ($this->isOnWaitingList()) {
            $this->dispatch('toast', 'Payment cannot be processed for tickets on waiting list.', 'error');
            return;
        }

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

            // Set success message in session
            session()->flash('success', 'Booking completed successfully!');

            // Return the booking object for further processing
            return $booking;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking error: ' . $e->getMessage());
            $this->dispatch('toast', 'An error occurred while processing your booking. Please try again.', 'error');
        }
    }

    public function render()
    {
        return view('livewire.user.booking-process', [
            'totalPrice' => $this->getTotalPrice(),
            'totalTickets' => $this->getTotalTickets(),
        ])->layout('components.layouts.public');
    }


}
