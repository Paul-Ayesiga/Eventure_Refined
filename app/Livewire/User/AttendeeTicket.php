<?php

namespace App\Livewire\User;

use App\Models\Attendee;
use App\Models\Booking;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendeeTicket extends Component
{
    public $attendeeId;
    public $attendee;
    public $booking;
    public $event;
    public $qrCode;

    public function mount($attendeeId)
    {
        $this->attendeeId = $attendeeId;
        $this->loadAttendeeData();
    }

    protected function loadAttendeeData()
    {
        $this->attendee = Attendee::with(['booking.event', 'ticket'])->findOrFail($this->attendeeId);
        $this->booking = $this->attendee->booking;
        $this->event = $this->booking->event;

        // Generate QR code content
        $qrCodeContent = [
            'booking_ref' => $this->booking->booking_reference,
            'attendee_id' => $this->attendee->id,
            'ticket_id' => $this->attendee->ticket_id,
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

        // Generate QR code
        $this->qrCode = $this->generateQrCode($qrCodeContent);
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

    public function printTicket()
    {
        $this->dispatch('print-ticket');
    }

    public function downloadTicket()
    {
        try {
            // Create a temporary attendee object with the QR code
            $attendeeWithQR = clone $this->attendee;

            // Check if the QR code is a data URI or a regular URL
            if (strpos($this->qrCode, 'data:image') === 0) {
                // It's already a data URI, use it as is
                $attendeeWithQR->qrCode = $this->qrCode;
            } else {
                // Generate a new QR code specifically for the PDF
                $qrCodeContent = [
                    'booking_ref' => $this->booking->booking_reference,
                    'attendee_id' => $this->attendee->id,
                    'ticket_id' => $this->attendee->ticket_id,
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

                // Generate QR code as SVG for better compatibility with PDF
                try {
                    // First try SVG format
                    $qrCode = QrCode::format('svg')
                                    ->size(200)
                                    ->errorCorrection('H')
                                    ->margin(1)
                                    ->generate($qrCodeContent);

                    $attendeeWithQR->qrCode = 'data:image/svg+xml;base64,' . base64_encode($qrCode);
                } catch (\Exception $qrException) {
                    // Fallback to PNG if SVG fails
                    try {
                        $qrCode = QrCode::format('png')
                                        ->size(200)
                                        ->errorCorrection('H')
                                        ->margin(1)
                                        ->generate($qrCodeContent);

                        $attendeeWithQR->qrCode = 'data:image/png;base64,' . base64_encode($qrCode);
                    } catch (\Exception $pngException) {
                        // If both fail, log the error and set qrCode to null
                        Log::warning('Failed to generate QR code for PDF', [
                            'svg_error' => $qrException->getMessage(),
                            'png_error' => $pngException->getMessage(),
                            'attendee_id' => $this->attendeeId
                        ]);

                        $attendeeWithQR->qrCode = null;
                    }
                }
            }

            // Generate PDF using DomPDF
            $pdf = Pdf::loadView('pdf.tickets', [
                'attendees' => [$attendeeWithQR],
                'booking' => $this->booking,
                'event' => $this->event
            ]);

            // Set PDF options for better rendering
            $pdf->setPaper('a4');
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isRemoteEnabled', true);

            // Set filename
            $filename = "ticket-{$this->attendeeId}.pdf";

            // Return the PDF as a download
            return response()->streamDownload(
                fn () => print($pdf->output()),
                $filename,
                ['Content-Type' => 'application/pdf']
            );
        } catch (\Exception $e) {
            // Log the error
            \Illuminate\Support\Facades\Log::error('PDF generation failed: ' . $e->getMessage(), [
                'exception' => $e,
                'attendee_id' => $this->attendeeId
            ]);

            // Show an error message to the user
            session()->flash('error', 'Failed to generate PDF ticket. Please try again later.');

            // Redirect back
            return redirect()->back();
        }
    }

    public function shareTicket()
    {
        // Generate a shareable URL for the ticket
        $shareUrl = route('user.attendee.ticket', ['attendeeId' => $this->attendeeId]);

        // Generate a message with event details
        $eventName = $this->event->name;
        $eventDate = $this->event->start_datetime->format('M d, Y, h:i A');
        $eventLocation = $this->event->venue ?: 'Online Event';

        $shareMessage = "Join me at {$eventName} on {$eventDate} at {$eventLocation}. Here's my ticket: ";

        // Dispatch event to update share links in the modal
        $this->dispatch('share-ticket', [
            'url' => $shareUrl,
            'message' => $shareMessage
        ]);
    }

    public function render()
    {
        return view('livewire.user.attendee-ticket');
    }
}
