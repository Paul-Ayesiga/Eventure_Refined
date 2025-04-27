<?php

namespace App\Livewire\User;

use App\Models\Booking;
use App\Models\Attendee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $this->booking = Booking::with(['event', 'bookingItems.ticket', 'dates'])
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
        try {
            // Get all attendees with QR codes
            $attendeesWithQR = [];

            foreach ($this->attendees as $attendee) {
                $attendeeWithQR = clone $attendee;

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

                // Generate QR code
                try {
                    $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                                ->size(200)
                                ->errorCorrection('H')
                                ->margin(1)
                                ->generate($qrCodeContent);

                    $attendeeWithQR->qrCode = 'data:image/svg+xml;base64,' . base64_encode($qrCode);
                } catch (\Exception $e) {
                    // If SVG fails, try PNG
                    try {
                        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')
                                    ->size(200)
                                    ->errorCorrection('H')
                                    ->margin(1)
                                    ->generate($qrCodeContent);

                        $attendeeWithQR->qrCode = 'data:image/png;base64,' . base64_encode($qrCode);
                    } catch (\Exception $e) {
                        // If both fail, set qrCode to null
                        $attendeeWithQR->qrCode = null;
                    }
                }

                $attendeesWithQR[] = $attendeeWithQR;
            }

            // Generate PDF using DomPDF
            $pdf = Pdf::loadView('pdf.tickets', [
                'attendees' => $attendeesWithQR,
                'booking' => $this->booking,
                'event' => $this->event
            ]);

            // Set PDF options for better rendering
            $pdf->setPaper('a4');
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isRemoteEnabled', true);

            // Set filename
            $filename = "tickets-{$this->booking->booking_reference}.pdf";

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
                'booking_id' => $this->bookingId
            ]);

            // Show an error message to the user
            session()->flash('error', 'Failed to generate PDF tickets. Please try again later.');

            // Redirect back
            return redirect()->back();
        }
    }

    public function printTickets()
    {
        // Generate QR codes for all attendees
        $attendeesWithQR = [];

        foreach ($this->attendees as $attendee) {
            $attendeeWithQR = clone $attendee;

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

            // Generate QR code
            try {
                $qrCode = QrCode::format('svg')
                            ->size(200)
                            ->errorCorrection('H')
                            ->margin(1)
                            ->generate($qrCodeContent);

                $attendeeWithQR->qrCode = 'data:image/svg+xml;base64,' . base64_encode($qrCode);
            } catch (\Exception $svgException) {
                // If SVG fails, try PNG
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
                        'svg_error' => $svgException->getMessage(),
                        'png_error' => $pngException->getMessage(),
                        'attendee_id' => $attendee->id
                    ]);

                    $attendeeWithQR->qrCode = null;
                }
            }

            $attendeesWithQR[] = $attendeeWithQR;
        }

        // Store the attendees with QR codes in the session
        session(['print_attendees' => $attendeesWithQR]);

        // Dispatch the print event
        $this->dispatch('print-tickets', [
            'attendees' => $attendeesWithQR,
            'booking' => $this->booking,
            'event' => $this->event
        ]);
    }

    public function shareTicket($attendeeId)
    {
        // Redirect to the individual ticket share page
        return redirect()->route('user.attendee.ticket', ['attendeeId' => $attendeeId]);
    }

    public function render()
    {
        return view('livewire.user.ticket-view');
    }
}
