<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;

class TicketController extends Controller
{
    /**
     * Generate a ticket for an attendee
     */
    public function generateTicket(Request $request, $attendeeId)
    {
        // Get the attendee with related booking and event
        $attendee = Attendee::with(['booking.event', 'ticket'])->findOrFail($attendeeId);
        $booking = $attendee->booking;
        $event = $booking->event;

        // Generate QR code content (booking reference + attendee ID for verification)
        $qrCodeContent = [
            'booking_ref' => $booking->booking_reference,
            'attendee_id' => $attendee->id,
            'ticket_id' => $attendee->ticket_id,
            'event_id' => $event->id,
            'event_name' => $event->name
        ];

        // Add event dates information
        if ($booking->dates->isNotEmpty()) {
            $qrCodeContent['event_dates'] = $booking->dates->pluck('event_date')->toArray();
        } elseif ($booking->event_date) {
            $qrCodeContent['event_date'] = $booking->event_date;
        }

        // Encode the QR code content
        $qrCodeContent = json_encode($qrCodeContent);

        // Generate QR code image
        $qrCode = QrCode::format('png')
                        ->size(200)
                        ->errorCorrection('H')
                        ->generate($qrCodeContent);

        $qrCodeBase64 = base64_encode($qrCode);

        // Return view with ticket data
        return view('tickets.show', [
            'attendee' => $attendee,
            'booking' => $booking,
            'event' => $event,
            'qrCode' => $qrCodeBase64
        ]);
    }

    /**
     * Generate tickets for all attendees in a booking
     */
    public function generateBookingTickets(Request $request, $bookingId)
    {
        // Get the booking with related attendees and event
        $booking = Booking::with(['attendees.ticket', 'event'])->findOrFail($bookingId);
        $event = $booking->event;

        // Generate QR codes for each attendee
        $attendeesWithQrCodes = $booking->attendees->map(function($attendee) use ($booking, $event) {
            // Generate QR code content
            $qrCodeContent = [
                'booking_ref' => $booking->booking_reference,
                'attendee_id' => $attendee->id,
                'ticket_id' => $attendee->ticket_id,
                'event_id' => $event->id,
                'event_name' => $event->name
            ];

            // Add event dates information
            if ($booking->dates->isNotEmpty()) {
                $qrCodeContent['event_dates'] = $booking->dates->pluck('event_date')->toArray();
            } elseif ($booking->event_date) {
                $qrCodeContent['event_date'] = $booking->event_date;
            }

            // Encode the QR code content
            $qrCodeContent = json_encode($qrCodeContent);

            // Generate QR code image
            $qrCode = QrCode::format('png')
                            ->size(200)
                            ->errorCorrection('H')
                            ->generate($qrCodeContent);

            $attendee->qrCode = base64_encode($qrCode);
            return $attendee;
        });

        // Return view with all tickets data
        return view('tickets.booking', [
            'booking' => $booking,
            'event' => $event,
            'attendees' => $attendeesWithQrCodes
        ]);
    }

    /**
     * Verify a ticket using the QR code
     */
    public function verifyTicket(Request $request)
    {
        $data = json_decode($request->input('data'), true);

        if (!$data || !isset($data['booking_ref']) || !isset($data['attendee_id'])) {
            return response()->json(['valid' => false, 'message' => 'Invalid QR code data']);
        }

        $attendee = Attendee::with(['booking', 'ticket'])
            ->where('id', $data['attendee_id'])
            ->whereHas('booking', function($query) use ($data) {
                $query->where('booking_reference', $data['booking_ref']);
            })
            ->first();

        if (!$attendee) {
            return response()->json(['valid' => false, 'message' => 'Attendee not found']);
        }

        // Check if already checked in
        if ($attendee->check_in_status) {
            return response()->json([
                'valid' => true,
                'checked_in' => true,
                'message' => 'Attendee already checked in at ' . $attendee->check_in_time->format('M d, Y h:i A'),
                'attendee' => $attendee
            ]);
        }

        // Mark as checked in
        $attendee->checkIn();

        return response()->json([
            'valid' => true,
            'checked_in' => true,
            'message' => 'Attendee successfully checked in',
            'attendee' => $attendee
        ]);
    }

    /**
     * Download tickets as PDF
     */
    public function download(Request $request)
    {
        $bookingId = $request->input('bookingId');
        $attendeeId = $request->input('attendeeId');

        if ($bookingId) {
            return $this->downloadBookingTickets($bookingId);
        } elseif ($attendeeId) {
            return $this->downloadAttendeeTicket($attendeeId);
        }

        return redirect()->back()->with('error', 'No booking or attendee specified');
    }

    /**
     * Download tickets for a booking
     */
    private function downloadBookingTickets($bookingId)
    {
        $booking = Booking::with(['attendees.ticket', 'event.organisation'])->findOrFail($bookingId);
        $event = $booking->event;

        // Generate QR codes for each attendee
        $attendees = $booking->attendees->map(function($attendee) use ($booking, $event) {
            // Generate QR code content
            $qrCodeContent = [
                'booking_ref' => $booking->booking_reference,
                'attendee_id' => $attendee->id,
                'ticket_id' => $attendee->ticket_id,
                'event_id' => $event->id,
                'event_name' => $event->name
            ];

            // Add event dates information
            if ($booking->dates->isNotEmpty()) {
                $qrCodeContent['event_dates'] = $booking->dates->pluck('event_date')->toArray();
            } elseif ($booking->event_date) {
                $qrCodeContent['event_date'] = $booking->event_date;
            }

            // Encode the QR code content
            $qrCodeContent = json_encode($qrCodeContent);

            // Generate QR code
            $qrCode = QrCode::format('svg')
                            ->size(200)
                            ->errorCorrection('H')
                            ->margin(1)
                            ->generate($qrCodeContent);

            $attendee->qrCode = 'data:image/svg+xml;base64,' . base64_encode($qrCode);

            return $attendee;
        });

        $pdf = PDF::loadView('pdf.tickets', [
            'attendees' => $attendees,
            'booking' => $booking,
            'event' => $event
        ]);

        return $pdf->download("tickets-{$booking->booking_reference}.pdf");
    }

    /**
     * Download ticket for a single attendee
     */
    private function downloadAttendeeTicket($attendeeId)
    {
        $attendee = Attendee::with(['booking.event.organisation', 'ticket'])->findOrFail($attendeeId);
        $booking = $attendee->booking;
        $event = $booking->event;

        // Generate QR code content
        $qrCodeContent = [
            'booking_ref' => $booking->booking_reference,
            'attendee_id' => $attendee->id,
            'ticket_id' => $attendee->ticket_id,
            'event_id' => $event->id,
            'event_name' => $event->name
        ];

        // Add event dates information
        if ($booking->dates->isNotEmpty()) {
            $qrCodeContent['event_dates'] = $booking->dates->pluck('event_date')->toArray();
        } elseif ($booking->event_date) {
            $qrCodeContent['event_date'] = $booking->event_date;
        }

        // Encode the QR code content
        $qrCodeContent = json_encode($qrCodeContent);

        // Generate QR code
        $qrCode = QrCode::format('svg')
                        ->size(200)
                        ->errorCorrection('H')
                        ->margin(1)
                        ->generate($qrCodeContent);

        $attendee->qrCode = 'data:image/svg+xml;base64,' . base64_encode($qrCode);

        $pdf = PDF::loadView('pdf.tickets', [
            'attendees' => [$attendee],
            'booking' => $booking,
            'event' => $event
        ]);

        return $pdf->download("ticket-{$attendee->id}.pdf");
    }
}
