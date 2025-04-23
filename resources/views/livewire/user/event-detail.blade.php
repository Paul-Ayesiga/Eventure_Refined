<div class="bg-gray-100 dark:bg-gray-900 min-h-screen pb-12">
    <!-- Event Header -->
    <div class="relative bg-cover bg-center h-64 md:h-96"
        style="background-image: url('{{ !empty($event->banners) && is_array($event->banners) && count($event->banners) > 0 ? $event->banners[0] : 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80' }}');">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative container mx-auto px-4 h-full flex flex-col justify-end pb-8">
            <!-- Back Button -->
            <a href="{{ route('user.events') }}" class="inline-flex items-center text-white mb-4 hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Events
            </a>

            <!-- Event Title -->
            <h1 class="text-white text-3xl md:text-5xl font-bold">{{ $event->name }}</h1>

            <!-- Event Meta -->
            <div class="flex flex-wrap gap-4 mt-4">
                <!-- Date -->
                <div class="flex items-center text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    @if ($event->event_repeat === 'Does not repeat')
                        {{ \Carbon\Carbon::parse($event->start_date)->format('D, M d, Y') }}
                    @else
                        {{ \Carbon\Carbon::parse($event->start_date)->format('M d') }} -
                        {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}
                    @endif
                </div>

                <!-- Time -->
                <div class="flex items-center text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ \Carbon\Carbon::parse($event->start_datetime)->format('g:i A') }} -
                    {{ \Carbon\Carbon::parse($event->end_datetime)->format('g:i A') }}
                </div>

                <!-- Location -->
                <div class="flex items-center text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    @if ($event->event_type === 'Online Event')
                        Online Event
                    @elseif($event->location)
                        {{ $event->location->display_place ?? $event->venue }}
                    @else
                        {{ $event->venue ?? 'Location TBD' }}
                    @endif
                </div>

                <!-- Organizer -->
                <div class="flex items-center text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    {{ $event->organisation->name }}
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Event Details -->
            <div class="lg:col-span-2">
                <!-- Description -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">About This Event</h2>
                    <div class="prose dark:prose-invert max-w-none">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </div>

                <!-- Location Details (if not online) -->
                @if ($event->event_type !== 'Online Event' && $event->location)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Location</h2>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                    {{ $event->location->display_place }}</h3>
                                <p class="text-gray-600 dark:text-gray-300">{{ $event->location->display_address }}</p>
                            </div>
                        </div>

                        <!-- Map Placeholder -->
                        <div class="mt-4 h-64 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                            <!-- Map will be loaded here -->
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="text-gray-500 dark:text-gray-400">Map loading...</span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Date & Time Details -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Date and Time</h2>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            @if ($event->event_repeat === 'Does not repeat')
                                <div class="mb-2">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                        {{ \Carbon\Carbon::parse($event->start_date)->format('l, F d, Y') }}</h3>
                                    <p class="text-gray-600 dark:text-gray-300">
                                        {{ \Carbon\Carbon::parse($event->start_datetime)->format('g:i A') }} -
                                        {{ \Carbon\Carbon::parse($event->end_datetime)->format('g:i A') }}
                                        ({{ $event->timezone }})</p>
                                </div>
                            @else
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">
                                    {{ $event->event_repeat }} Event</h3>
                                <p class="text-gray-600 dark:text-gray-300 mb-2">
                                    {{ \Carbon\Carbon::parse($event->start_date)->format('F d, Y') }} -
                                    {{ \Carbon\Carbon::parse($event->end_date)->format('F d, Y') }}</p>
                                <p class="text-gray-600 dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($event->start_datetime)->format('g:i A') }} -
                                    {{ \Carbon\Carbon::parse($event->end_datetime)->format('g:i A') }}
                                    ({{ $event->timezone }})</p>

                                <!-- Date Selection for Multi-date Events -->
                                @if (count($availableDates) > 1)
                                    <div class="mt-4">
                                        <label for="date-select"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select
                                            Date:</label>
                                        <select id="date-select" wire:model.live="selectedDate"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                            @foreach ($availableDates as $dateKey => $dateLabel)
                                                <option value="{{ $dateKey }}">{{ $dateLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ticket Selection & Checkout -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-4">
                    <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Tickets</h2>

                    @if (count($tickets) > 0)
                        <!-- Ticket Options -->
                        <div class="space-y-4 mb-6">
                            @foreach ($tickets as $ticket)
                                <div class="border dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h3 class="font-semibold text-gray-800 dark:text-white">
                                                {{ $ticket->name }}</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $ticket->description }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                class="font-bold text-gray-800 dark:text-white">{{ $event->currency }}
                                                {{ number_format($ticket->price, 2) }}</span>
                                        </div>
                                    </div>

                                    <!-- Quantity Selector -->
                                    <div class="flex items-center justify-between mt-3">
                                        <span
                                            class="text-sm text-gray-600 dark:text-gray-400">{{ $ticket->getRemainingQuantity() }}
                                            remaining</span>
                                        <div class="flex items-center">
                                            <button wire:click="decrementTicket({{ $ticket->id }})"
                                                class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600"
                                                @if ($selectedTickets[$ticket->id] === 0) disabled @endif>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M20 12H4" />
                                                </svg>
                                            </button>
                                            <span
                                                class="mx-3 w-6 text-center font-medium text-gray-800 dark:text-white">{{ $selectedTickets[$ticket->id] }}</span>
                                            <button wire:click="incrementTicket({{ $ticket->id }})"
                                                class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600"
                                                @if ($selectedTickets[$ticket->id] >= min($ticket->max_tickets_per_booking, $ticket->getRemainingQuantity())) disabled @endif>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Summary -->
                        <div class="border-t dark:border-gray-700 pt-4 mb-6">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600 dark:text-gray-400">Tickets ({{ $totalTickets }})</span>
                                <span class="font-medium text-gray-800 dark:text-white">{{ $event->currency }}
                                    {{ number_format($totalPrice, 2) }}</span>
                            </div>
                            <div class="flex justify-between font-bold">
                                <span class="text-gray-800 dark:text-white">Total</span>
                                <span class="text-gray-800 dark:text-white">{{ $event->currency }}
                                    {{ number_format($totalPrice, 2) }}</span>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <button wire:click="proceedToBooking"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-md font-medium transition-colors duration-300 {{ $totalTickets === 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                            @if ($totalTickets === 0) disabled @endif>
                            Proceed to Checkout
                        </button>
                    @else
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-1">No Tickets Available
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400">There are currently no tickets available for
                                this event.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
