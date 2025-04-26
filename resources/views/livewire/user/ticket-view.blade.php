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
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Your Tickets</h2>
                            <div class="flex space-x-2">
                                <button wire:click="printTickets"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    Print All
                                </button>
                                <button wire:click="downloadTickets"
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Download All
                                </button>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @foreach ($attendees as $attendee)
                                <div class="border dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
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
                                        <button wire:click="shareTicket({{ $attendee->id }})"
                                            class="inline-flex items-center px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-xs font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                            </svg>
                                            Share
                                        </button>
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
                                @foreach ($booking->items as $item)
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
                            <a href="{{ route('user.event.detail', $event->id) }}"
                                class="block w-full px-4 py-2 bg-blue-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-center">
                                View Event Details
                            </a>
                            <a href="{{ route('user.bookings') }}"
                                class="block w-full mt-3 px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-center">
                                View All Bookings
                            </a>
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
                <a href="{{ route('user.bookings') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    View Your Bookings
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', function() {
            // Handle print tickets event
            @this.on('print-tickets', function() {
                window.print();
            });
        });
    </script>
@endpush
