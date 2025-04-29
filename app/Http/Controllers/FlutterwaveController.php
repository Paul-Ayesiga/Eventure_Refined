<?php

namespace App\Http\Controllers;

use App\Models\FlutterwaveTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class FlutterwaveController extends Controller
{
    /**
     * Handle the callback from Flutterwave payment
     */
    public function handleCallback(Request $request)
    {
        Log::info('Flutterwave callback received', $request->all());

        // Get the transaction reference from the request
        $status = $request->status;
        $txRef = $request->tx_ref;
        $flwRef = $request->transaction_id;

        // Verify the transaction
        if ($status === 'successful' && $txRef && $flwRef) {
            // Start a database transaction
            DB::beginTransaction();

            try {
                // Find the transaction in our database
                $transaction = FlutterwaveTransaction::where('tx_ref', $txRef)->first();

                // Extract event ID from request or transaction
                $eventId = $request->input('event_id');

                if ($transaction) {
                    // Update transaction status
                    $transaction->status = $status;
                    $transaction->flw_ref = $flwRef;
                    $transaction->save();

                    // Get event ID from transaction metadata if not in request
                    if (!$eventId && isset($transaction->metadata['event_id'])) {
                        $eventId = $transaction->metadata['event_id'];
                    }
                } else {
                    // If transaction doesn't exist, log an error - we should always have a pending transaction
                    Log::error('No transaction found for Flutterwave callback', [
                        'tx_ref' => $txRef,
                        'status' => $status,
                        'request_data' => $request->all()
                    ]);

                    // Create a transaction record as a fallback
                    $userId = $request->input('user_id') ?? (Auth::check() ? Auth::id() : null);
                    $amount = $request->input('amount');
                    $currency = config('flutterwave.currency', 'UGX');

                    // Get tickets data from request
                    $selectedTickets = [];
                    if ($request->has('tickets')) {
                        // Direct from URL parameter
                        $selectedTickets = json_decode($request->input('tickets'), true);
                    } elseif ($request->has('meta') && isset($request->meta['tickets'])) {
                        // From meta data
                        $selectedTickets = json_decode($request->meta['tickets'], true);
                    }

                    // Log the tickets data for debugging
                    Log::info('Selected tickets data:', [
                        'tickets' => $selectedTickets,
                        'source' => $request->has('tickets') ? 'request_param' : ($request->has('meta') ? 'meta_data' : 'none')
                    ]);

                    // Create transaction record
                    $transaction = new FlutterwaveTransaction([
                        'user_id' => $userId,
                        'amount' => $amount,
                        'currency' => $currency,
                        'display_currency' => $currency,
                        'tx_ref' => $txRef,
                        'flw_ref' => $flwRef,
                        'status' => $status,
                        'metadata' => [
                            'event_id' => $eventId,
                            'user_id' => $userId,
                            'tickets' => $selectedTickets,
                            'selected_date' => $request->input('selected_date')
                        ]
                    ]);
                    $transaction->save();

                    Log::info('Created new Flutterwave transaction record as fallback', [
                        'tx_ref' => $txRef,
                        'status' => $status
                    ]);
                }

                // Store the transaction reference in session for the Livewire component to process
                Session::flash('flutterwave_payment_success', true);
                Session::flash('flutterwave_tx_ref', $txRef);
                Session::flash('flutterwave_flw_ref', $flwRef);

                // Create the booking if it doesn't exist yet
                if (!$transaction->booking_id && $eventId) {
                    // We need to create a booking
                    // Verify the event and user exist
                    \App\Models\Event::findOrFail($eventId);
                    \App\Models\User::findOrFail($userId);

                    // Get the tickets from the transaction metadata or request
                    $selectedTickets = $transaction->metadata['tickets'] ?? [];

                    // If no tickets in metadata, try to get from request
                    if (empty($selectedTickets) && $request->has('tickets')) {
                        $selectedTickets = json_decode($request->input('tickets'), true);

                        // Update transaction metadata with tickets data
                        if (!empty($selectedTickets)) {
                            $metadata = $transaction->metadata ?? [];
                            $metadata['tickets'] = $selectedTickets;
                            $transaction->metadata = $metadata;
                            $transaction->save();

                            Log::info('Updated transaction metadata with tickets from request', [
                                'tx_ref' => $txRef,
                                'tickets' => $selectedTickets
                            ]);
                        }
                    }

                    // Try to get attendees data from request or session
                    $attendeesData = [];
                    if ($request->has('attendees')) {
                        $attendeesData = json_decode($request->input('attendees'), true);
                    } elseif ($request->has('meta') && isset($request->meta['attendees'])) {
                        $attendeesData = json_decode($request->meta['attendees'], true);
                    } elseif (session()->has('booking_attendees')) {
                        $attendeesData = session('booking_attendees');
                    }

                    // Update transaction metadata with attendees data if available
                    if (!empty($attendeesData)) {
                        $metadata = $transaction->metadata ?? [];
                        $metadata['attendees'] = $attendeesData;
                        $transaction->metadata = $metadata;
                        $transaction->save();

                        Log::info('Updated transaction metadata with attendees data', [
                            'tx_ref' => $txRef,
                            'attendees_count' => count($attendeesData)
                        ]);
                    }

                    // Log the tickets data
                    Log::info('Tickets data for booking creation:', [
                        'tickets' => $selectedTickets,
                        'source' => isset($transaction->metadata['tickets']) ? 'transaction_metadata' :
                                   ($request->has('tickets') ? 'request_param' : 'unknown')
                    ]);

                    if (!empty($selectedTickets)) {
                        // Create the booking
                        $booking = new \App\Models\Booking([
                            'event_id' => $eventId,
                            'user_id' => $userId,
                            'booking_reference' => 'BK' . strtoupper(uniqid()),
                            'status' => 'confirmed',
                            'total_amount' => $transaction->amount,
                            'payment_status' => 'paid',
                        ]);
                        $booking->save();

                        // Link the transaction to the booking
                        $transaction->booking_id = $booking->id;
                        $transaction->save();

                        // Create booking date
                        $selectedDate = $request->input('selected_date') ?? now()->format('Y-m-d');
                        \App\Models\BookingDate::create([
                            'booking_id' => $booking->id,
                            'event_date' => $selectedDate,
                        ]);

                        // Create booking items
                        $tickets = \App\Models\Ticket::whereIn('id', array_keys($selectedTickets))->get();
                        foreach ($selectedTickets as $ticketId => $quantity) {
                            if ($quantity > 0) {
                                $ticket = $tickets->firstWhere('id', $ticketId);
                                if ($ticket) {
                                    \App\Models\BookingItem::create([
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
                        }

                        // Create attendees if we have attendee data
                        $attendeesData = $transaction->metadata['attendees'] ?? [];

                        if (!empty($attendeesData)) {
                            Log::info('Creating attendees from transaction metadata', [
                                'attendees_count' => count($attendeesData),
                                'booking_id' => $booking->id
                            ]);

                            foreach ($attendeesData as $attendeeData) {
                                \App\Models\Attendee::create([
                                    'booking_id' => $booking->id,
                                    'ticket_id' => $attendeeData['ticket_id'],
                                    'first_name' => $attendeeData['first_name'],
                                    'last_name' => $attendeeData['last_name'],
                                    'email' => $attendeeData['email'],
                                    'phone' => $attendeeData['phone'] ?? '',
                                    'check_in_status' => false,
                                ]);
                            }
                        } else {
                            // If no attendee data, create default attendees based on tickets
                            Log::info('No attendee data found, creating default attendees', [
                                'booking_id' => $booking->id
                            ]);

                            // Get user info for default attendee
                            $user = \App\Models\User::find($userId);
                            if ($user) {
                                $names = explode(' ', $user->name, 2);
                                $firstName = $names[0];
                                $lastName = isset($names[1]) ? $names[1] : '';

                                // Create one attendee per ticket quantity
                                foreach ($selectedTickets as $ticketId => $quantity) {
                                    if ($quantity > 0) {
                                        $ticket = $tickets->firstWhere('id', $ticketId);
                                        if ($ticket) {
                                            for ($i = 0; $i < $quantity; $i++) {
                                                \App\Models\Attendee::create([
                                                    'booking_id' => $booking->id,
                                                    'ticket_id' => $ticketId,
                                                    'first_name' => $firstName,
                                                    'last_name' => $lastName,
                                                    'email' => $user->email,
                                                    'phone' => '',
                                                    'check_in_status' => false,
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        // Store the booking ID in the session for redirect
                        Session::put('completed_booking_id', $booking->id);

                        // Commit the transaction
                        DB::commit();

                        // Redirect to the tickets view
                        return redirect()->route('tickets.view', ['bookingId' => $booking->id])
                            ->with('success', 'Payment successful! Your booking has been confirmed.');
                    }
                } else if ($transaction->booking_id) {
                    // Booking already exists, redirect to it
                    DB::commit();
                    return redirect()->route('tickets.view', ['bookingId' => $transaction->booking_id])
                        ->with('success', 'Payment successful! Your booking has been confirmed.');
                }

                // If we get here, we couldn't create a booking, but the payment was successful
                DB::commit();

                // Log this situation as it shouldn't normally happen
                Log::warning('Payment successful but booking creation failed', [
                    'tx_ref' => $txRef,
                    'event_id' => $eventId,
                    'user_id' => $userId ?? 'unknown',
                    'selected_tickets' => $selectedTickets ?? 'unknown'
                ]);

                // Try to create the booking again with the Livewire component
                if ($eventId) {
                    // Store event ID in session for the Livewire component to use
                    Session::put('flutterwave_event_id', $eventId);

                    // Try to find any booking associated with this transaction
                    $booking = \App\Models\Booking::where('user_id', $userId)
                        ->where('event_id', $eventId)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    if ($booking) {
                        // If we found a booking, redirect to the tickets view
                        return redirect()->route('tickets.view', ['bookingId' => $booking->id])
                            ->with('success', 'Payment successful! Your booking has been confirmed.');
                    } else {
                        // Redirect to user bookings if no specific booking found
                        return redirect()->route('user.bookings')
                            ->with('success', 'Payment successful! Your booking has been processed.');
                    }
                } else {
                    // Fallback to home page if we can't determine the event ID
                    return redirect()->route('home')
                        ->with('success', 'Payment successful! Please check your bookings.');
                }
            } catch (\Exception $e) {
                // Roll back the transaction if an error occurs
                DB::rollBack();

                Log::error('Error processing Flutterwave callback: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString(),
                    'tx_ref' => $txRef
                ]);

                return redirect()->route('home')
                    ->with('error', 'An error occurred while processing your payment. Please contact support.');
            }
        } else {
            Log::warning('Flutterwave payment failed', [
                'status' => $status,
                'tx_ref' => $txRef
            ]);
            return redirect()->route('home')->with('error', 'Payment was not successful. Please try again.');
        }
    }

    /**
     * Handle webhook notifications from Flutterwave
     */
    public function handleWebhook(Request $request)
    {
        Log::info('Flutterwave webhook received', $request->all());

        // Verify webhook signature
        $signature = $request->header('verif-hash');
        $secretHash = config('flutterwave.webhook_secret');

        if (!$signature || ($secretHash && $signature !== $secretHash)) {
            Log::warning('Invalid Flutterwave webhook signature', [
                'received' => $signature,
                'expected' => $secretHash
            ]);
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 401);
        }

        // Process the webhook
        $payload = $request->all();

        if (isset($payload['event']) && $payload['event'] === 'charge.completed') {
            $data = $payload['data'];
            $txRef = $data['tx_ref'];
            $flwRef = $data['id'];
            $status = $data['status'];

            // Find the transaction
            $transaction = FlutterwaveTransaction::where('tx_ref', $txRef)->first();

            if ($transaction) {
                // Update transaction status
                $transaction->status = $status;
                $transaction->flw_ref = $flwRef;
                $transaction->save();

                Log::info('Flutterwave transaction updated via webhook', [
                    'tx_ref' => $txRef,
                    'status' => $status
                ]);
            } else {
                Log::error('Flutterwave transaction not found for webhook', ['tx_ref' => $txRef]);
            }
        }

        return response()->json(['status' => 'success']);
    }
}
