<?php

namespace App\Livewire\Org\Events;

use App\Models\Attendee;
use App\Models\Booking;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class GenerateTicket extends Component
{
    public $attendeeId;
    public $bookingId;
    public $showAllTickets = false;
    public $attendees = [];
    public $booking;
    public $event;

    public function mount($bookingId = null, $attendeeId = null)
    {
        $this->bookingId = $bookingId;
        $this->attendeeId = $attendeeId;

        if ($this->bookingId) {
            $this->loadBookingData();
        } elseif ($this->attendeeId) {
            $this->loadAttendeeData();
        }
    }

    public function loadBookingData()
    {
        $this->booking = Booking::with(['attendees.ticket', 'event'])->findOrFail($this->bookingId);
        $this->event = $this->booking->event;

        // Generate QR codes for each attendee
        $this->attendees = $this->booking->attendees->map(function($attendee) {
            // Generate QR code content
            $qrCodeContent = [
                'booking_ref' => $this->booking->booking_reference,
                'attendee_id' => $attendee->id,
                'ticket_id' => $attendee->ticket_id,
                'event_id' => $this->event->id,
                'event_name' => $this->event->name
            ];

            // Add event dates information
            if ($this->booking->dates->isNotEmpty()) {
                $qrCodeContent['event_dates'] = $this->booking->dates->pluck('event_date')->toArray();
            } elseif ($this->booking->event_date) {
                $qrCodeContent['event_date'] = $this->booking->event_date;
            }

            // Encode the QR code content
            $qrCodeContent = json_encode($qrCodeContent);

            // Generate QR code using our local method
            $attendee->qrCode = $this->generateQrCode($qrCodeContent);

            return $attendee;
        })->all(); // Use all() instead of toArray() to keep object properties

        $this->showAllTickets = true;
    }



    public function loadAttendeeData()
    {
        $attendee = Attendee::with(['booking.event', 'ticket'])->findOrFail($this->attendeeId);
        $this->booking = $attendee->booking;
        $this->event = $this->booking->event;

        // Generate QR code content
        $qrCodeContent = [
            'booking_ref' => $this->booking->booking_reference,
            'attendee_id' => $attendee->id,
            'ticket_id' => $attendee->ticket_id,
            'event_id' => $this->event->id,
            'event_name' => $this->event->name
        ];

        // Add event dates information
        if ($this->booking->dates->isNotEmpty()) {
            $qrCodeContent['event_dates'] = $this->booking->dates->pluck('event_date')->toArray();
        } elseif ($this->booking->event_date) {
            $qrCodeContent['event_date'] = $this->booking->event_date;
        }

        // Encode the QR code content
        $qrCodeContent = json_encode($qrCodeContent);

        // Generate QR code using our local method
        $attendee->qrCode = $this->generateQrCode($qrCodeContent);

        $this->attendees = [$attendee];
        $this->showAllTickets = false;
    }

    public function printTickets()
    {
        // Regenerate QR codes for each attendee
        foreach ($this->attendees as $attendee) {
            // Generate QR code content
            $qrCodeContent = [
                'booking_ref' => $this->booking->booking_reference,
                'attendee_id' => $attendee->id,
                'ticket_id' => $attendee->ticket_id,
                'event_id' => $this->event->id,
                'event_name' => $this->event->name
            ];

            // Add event dates information
            if ($this->booking->dates->isNotEmpty()) {
                $qrCodeContent['event_dates'] = $this->booking->dates->pluck('event_date')->toArray();
            } elseif ($this->booking->event_date) {
                $qrCodeContent['event_date'] = $this->booking->event_date;
            }

            // Encode the QR code content
            $qrCodeContent = json_encode($qrCodeContent);

            // Generate QR code and set it as a property
            $attendee->qrCode = $this->generateQrCode($qrCodeContent);
        }

        $this->dispatch('print-tickets');
    }

    public function downloadTickets()
    {
        // Regenerate QR codes for each attendee
        foreach ($this->attendees as $attendee) {
            // Generate QR code content
            $qrCodeContent = [
                'booking_ref' => $this->booking->booking_reference,
                'attendee_id' => $attendee->id,
                'ticket_id' => $attendee->ticket_id,
                'event_id' => $this->event->id,
                'event_name' => $this->event->name
            ];

            // Add event dates information
            if ($this->booking->dates->isNotEmpty()) {
                $qrCodeContent['event_dates'] = $this->booking->dates->pluck('event_date')->toArray();
            } elseif ($this->booking->event_date) {
                $qrCodeContent['event_date'] = $this->booking->event_date;
            }

            // Encode the QR code content
            $qrCodeContent = json_encode($qrCodeContent);

            // Generate QR code and set it as a property
            $attendee->qrCode = $this->generateQrCode($qrCodeContent);
        }

        // Generate PDF using DomPDF
        $pdf = PDF::loadView('pdf.tickets', [
            'attendees' => $this->attendees,
            'booking' => $this->booking,
            'event' => $this->event
        ]);

        // Set filename
        $filename = $this->attendeeId
            ? "ticket-{$this->attendeeId}.pdf"
            : "tickets-{$this->booking->booking_reference}.pdf";

        // Return the PDF as a download
        return response()->streamDownload(
            fn () => print($pdf->output()),
            $filename,
            ['Content-Type' => 'application/pdf']
        );
    }

    /**
     * Share tickets via social media and other channels
     */
    public function shareTickets()
    {
        // Regenerate QR codes for each attendee
        foreach ($this->attendees as $attendee) {
            // Generate QR code content
            $qrCodeContent = [
                'booking_ref' => $this->booking->booking_reference,
                'attendee_id' => $attendee->id,
                'ticket_id' => $attendee->ticket_id,
                'event_id' => $this->event->id,
                'event_name' => $this->event->name
            ];

            // Add event dates information
            if ($this->booking->dates->isNotEmpty()) {
                $qrCodeContent['event_dates'] = $this->booking->dates->pluck('event_date')->toArray();
            } elseif ($this->booking->event_date) {
                $qrCodeContent['event_date'] = $this->booking->event_date;
            }

            // Encode the QR code content
            $qrCodeContent = json_encode($qrCodeContent);

            // Generate QR code and set it as a property
            $attendee->qrCode = $this->generateQrCode($qrCodeContent);
        }

        // Generate a shareable URL for the tickets
        $shareUrl = route('tickets.view', [
            'bookingId' => $this->bookingId,
            'attendeeId' => $this->attendeeId
        ]);

        // Dispatch event to update share links in the modal
        $this->dispatch('share-tickets', [
            'url' => $shareUrl
        ]);
    }

    /**
     * Generate a QR code using SimpleSoftwareIO QrCode
     */
    protected function generateQrCode($content)
    {
        // Generate a data URI for the QR code
        $qrCode = QrCode::format('svg')
                        ->size(200)
                        ->errorCorrection('H')
                        ->margin(1)
                        ->generate($content);

        return 'data:image/svg+xml;base64,' . base64_encode($qrCode);
    }

    public function render()
    {
        return view('livewire.org.events.generate-ticket')->layout('components.layouts.event-detail', [
            'eventId' => $this->event->id,
            'event' => $this->event
        ]);
    }
}
