<div class="bg-gray-100 dark:bg-gray-900 min-h-screen pb-12">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-md">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <!-- Back Button -->
                <a href="{{ route('user.events') }}"
                    class="inline-flex items-center text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Events
                </a>

                <!-- Page Title -->
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">Your Tickets</h1>

                <!-- Empty div for spacing -->
                <div class="w-24"></div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        @if ($showSuccessMessage)
            <div class="mb-8 p-4 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 dark:text-green-300">
                            <span class="font-medium">Success!</span> Your booking has been confirmed and your tickets
                            are ready.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if ($booking)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Booking Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Booking Confirmation</h2>

                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600 dark:text-gray-400">Booking Reference:</span>
                                <span
                                    class="font-medium text-gray-800 dark:text-white">{{ $booking->booking_reference }}</span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600 dark:text-gray-400">Date:</span>
                                <span class="font-medium text-gray-800 dark:text-white">
                                    {{ $booking->created_at->format('F d, Y') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-gray-600 dark:text-gray-400">Status:</span>
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full
                                    {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Payment Status:</span>
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full
                                    {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' }}">
                                    {{ ucfirst($booking->payment_status) }}
                                </span>
                            </div>
                        </div>

                        <div class="border-t dark:border-gray-700 pt-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Event Details</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <h4 class="font-medium text-gray-800 dark:text-white">{{ $event->name }}</h4>
                                <p class="text-gray-600 dark:text-gray-300 mt-1">
                                    @if ($booking->dates->isNotEmpty())
                                        {{ \Carbon\Carbon::parse($booking->dates->first()->event_date)->format('l, F d, Y') }}
                                    @else
                                        {{ \Carbon\Carbon::parse($event->start_datetime)->format('l, F d, Y') }}
                                    @endif
                                </p>
                                <p class="text-gray-600 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($event->start_datetime)->format('g:i A') }} -
                                    {{ \Carbon\Carbon::parse($event->end_datetime)->format('g:i A') }}
                                </p>
                                <p class="text-gray-600 dark:text-gray-300 mt-1">
                                    @if ($event->event_type === 'Online Event')
                                        Online Event
                                    @elseif($event->location)
                                        {{ $event->location->display_place }}
                                    @else
                                        {{ $event->venue ?? 'Location TBD' }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Tickets -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Your Tickets</h2>
                            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                                <flux:button wire:click="printTickets" icon="printer" variant="outline"
                                    class="w-full sm:w-auto">
                                    <span class="hidden sm:inline">Print All</span>
                                    <span class="sm:hidden">Print</span>
                                </flux:button>
                                <flux:button wire:click="downloadTickets" icon="arrow-down-tray" variant="primary"
                                    class="w-full sm:w-auto">
                                    <span class="hidden sm:inline">Download All</span>
                                    <span class="sm:hidden">Download</span>
                                </flux:button>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @foreach ($attendees as $attendee)
                                <div class="border dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex flex-col sm:flex-row justify-between items-start gap-3">
                                        <div>
                                            <h3 class="font-semibold text-gray-800 dark:text-white">
                                                {{ $attendee->first_name }} {{ $attendee->last_name }}</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $attendee->email }}
                                            </p>
                                            <div class="mt-2">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                    Ticket #{{ $attendee->id }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto mt-3 sm:mt-0">
                                            <flux:button
                                                href="{{ route('user.attendee.ticket', ['attendeeId' => $attendee->id]) }}"
                                                icon="eye" variant="outline" size="xs"
                                                class="w-full sm:w-auto">
                                                <span class="hidden sm:inline">View Ticket</span>
                                                <span class="sm:hidden">View</span>
                                            </flux:button>
                                            <flux:button wire:click="shareTicket({{ $attendee->id }})" icon="share"
                                                variant="outline" size="xs" class="w-full sm:w-auto">
                                                <span class="hidden sm:inline">Share Ticket</span>
                                                <span class="sm:hidden">Share</span>
                                            </flux:button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-4">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Order Summary</h2>

                        <!-- Tickets -->
                        <div class="mb-4 pb-4 border-b dark:border-gray-700">
                            <h3 class="font-semibold text-gray-800 dark:text-white mb-2">Tickets</h3>
                            <div class="space-y-2">
                                @foreach ($booking->bookingItems as $item)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">{{ $item->ticket->name }} x
                                            {{ $item->quantity }}</span>
                                        <span class="font-medium text-gray-800 dark:text-white">{{ $event->currency }}
                                            {{ number_format($item->subtotal, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="mb-4">
                            <div class="flex justify-between font-bold">
                                <span class="text-gray-800 dark:text-white">Total</span>
                                <span class="text-gray-800 dark:text-white">{{ $event->currency }}
                                    {{ number_format($booking->total_amount, 2) }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-6">
                            <flux:button href="{{ route('user.event.detail', $event->id) }}" class="w-full"
                                variant="primary">
                                View Event Details
                            </flux:button>
                            <div class="mt-3">
                                <flux:button href="{{ route('user.bookings') }}" class="w-full" variant="outline">
                                    View All Bookings
                                </flux:button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-16 w-16 mx-auto text-gray-400 dark:text-gray-500 mb-4" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">No Booking Found</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">We couldn't find the booking you're looking for.</p>
                <flux:button href="{{ route('user.bookings') }}" variant="primary">
                    View Your Bookings
                </flux:button>
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', function() {
            // Handle print tickets event
            @this.on('print-tickets', function(data) {
                // Create a print-friendly version with all tickets
                const originalContents = document.body.innerHTML;

                // Start building the print content
                let printContent = `
                    <style>
                        @media print {
                            body { margin: 0; padding: 20px; background-color: white; }
                            .ticket-container {
                                max-width: 100%;
                                margin-bottom: 30px;
                                border: 1px solid #e2e8f0;
                                border-radius: 8px;
                                overflow: hidden;
                                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                                page-break-after: always;
                            }
                            .ticket-header {
                                background: linear-gradient(to right, #3b82f6, #2dd4bf);
                                color: white;
                                padding: 16px;
                            }
                            .ticket-body {
                                padding: 24px;
                                display: flex;
                                flex-direction: row;
                                justify-content: space-between;
                            }
                            .ticket-details {
                                flex: 1;
                                padding-right: 16px;
                            }
                            .ticket-qr {
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                justify-content: center;
                            }
                            .qr-code-container {
                                border: 1px solid #e2e8f0;
                                padding: 8px;
                                background: white;
                            }
                            .qr-code-container img {
                                width: 160px;
                                height: 160px;
                            }
                            .ticket-footer {
                                background-color: #f9fafb;
                                border-top: 1px solid #e5e7eb;
                                padding: 12px;
                                text-align: center;
                                font-size: 12px;
                                color: #6b7280;
                            }
                            .event-name {
                                font-size: 18px;
                                font-weight: bold;
                                margin-bottom: 4px;
                            }
                            .org-name {
                                font-size: 14px;
                                opacity: 0.9;
                            }
                            .event-type {
                                display: inline-block;
                                padding: 2px 8px;
                                background-color: rgba(20, 184, 166, 0.3);
                                border-radius: 4px;
                                font-size: 14px;
                            }
                            .section-title {
                                font-size: 12px;
                                text-transform: uppercase;
                                color: #6b7280;
                                font-weight: 500;
                                margin-bottom: 4px;
                            }
                            .section-value {
                                font-weight: 600;
                                color: #111827;
                            }
                            .grid-2 {
                                display: grid;
                                grid-template-columns: 1fr 1fr;
                                gap: 24px;
                                margin-top: 24px;
                            }
                            .text-sm {
                                font-size: 14px;
                            }
                            .text-xs {
                                font-size: 12px;
                            }
                            .text-gray {
                                color: #6b7280;
                            }
                            .mb-6 {
                                margin-bottom: 24px;
                            }
                            .mb-3 {
                                margin-bottom: 12px;
                            }
                            .mt-3 {
                                margin-top: 12px;
                            }
                        }
                    </style>
                `;

                // Add each ticket to the print content
                if (data && data.attendees) {
                    data.attendees.forEach(attendee => {
                        printContent += `
                            <div class="ticket-container">
                                <div class="ticket-header">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div>
                                            <div class="event-name">${data.event.name}</div>
                                            <div class="org-name">${data.event.organisation.name}</div>
                                        </div>
                                        <div>
                                            <span class="event-type">${data.event.event_type || 'online'}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="ticket-body">
                                    <div class="ticket-details">
                                        <div class="mb-6">
                                            <p class="text-sm text-gray">Coming soon</p>
                                            <p class="section-value">${data.event.venue || 'Online Event'},
                                                ${data.event.location ? data.event.location.country : ''}</p>
                                            <p class="text-sm">${new Date(data.event.start_datetime).toLocaleString()} (${data.event.timezone})</p>
                                        </div>

                                        <div class="mb-6">
                                            <p class="section-title">ISSUED TO</p>
                                            <p class="section-value">${attendee.first_name} ${attendee.last_name}</p>
                                        </div>

                                        <div class="grid-2">
                                            <div>
                                                <p class="section-title">BOOKING REFERENCE</p>
                                                <p class="section-value">${data.booking.booking_reference}</p>
                                                <p class="text-xs text-gray mt-3">
                                                    Booked On<br>
                                                    ${new Date(data.booking.created_at).toLocaleDateString()}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="section-title">TICKET</p>
                                                <p class="section-value">${attendee.ticket.name}</p>
                                                <p class="text-xs text-gray">
                                                    ${attendee.ticket.price > 0 ?
                                                        parseFloat(attendee.ticket.price).toFixed(2) + ' ' + data.event.currency :
                                                        'FREE'}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="ticket-qr">
                                        <div class="qr-code-container">
                                            ${attendee.qrCode ?
                                                `<img src="${attendee.qrCode}" alt="QR Code">` :
                                                `<div style="width: 160px; height: 160px; display: flex; align-items: center; justify-content: center;">
                                                                            <p style="text-align: center; color: #6b7280;">QR Code<br>Not Available</p>
                                                                        </div>`
                                            }
                                        </div>
                                    </div>
                                </div>

                                <div class="ticket-footer">
                                    <p>© ${new Date().getFullYear()} ${data.event.organisation.name} - All Rights Reserved</p>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    // Fallback to basic print if no data is provided
                    printContent = `<p>No ticket data available for printing.</p>`;
                }

                // Set the print content
                document.body.innerHTML = printContent;

                // Print the document
                window.print();

                // Restore the original content
                document.body.innerHTML = originalContents;

                // Reinitialize Livewire
                window.Livewire.rescan();
            });
        });
    </script>
@endpush
