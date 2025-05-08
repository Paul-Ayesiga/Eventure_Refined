<div class="bg-gray-100 dark:bg-gray-900 min-h-screen pb-12">
    <!-- Event Header -->
    <div class="relative bg-cover bg-center h-64 md:h-96">
        <!-- Blur-up preview -->
        <div class="absolute inset-0 bg-cover bg-center filter blur-xl scale-110 transform opacity-50 transition-opacity duration-500"
            style="background-image: url('{{ $event->banner ? $event->banner . '?quality=1' : 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=1&w=20' }}');">
        </div>
        <!-- Main image -->
        <img src="{{ $event->banner ? $event->banner : 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=2070' }}"
            class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500"
            alt="{{ $event->name }}"
            loading="eager"
            onload="this.previousElementSibling.style.opacity = 0;"
            onerror="this.src='https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=2070'">
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/60"></div>
        <div class="relative container mx-auto px-4 h-full flex flex-col justify-end pb-8">
            <!-- Back Button -->
            <a href="{{ route('user.events') }}" class="inline-flex items-center text-white mb-4 hover:underline ">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Events
            </a>

            <!-- Event Title -->
            <div class="flex items-center gap-3">
                <h1 class="text-white text-3xl md:text-5xl font-bold">{{ $event->name }}</h1>
                @if ($event->isArchived())
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                        Archived
                    </span>
                @endif
            </div>

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

                        <!-- Map -->
                        <div class="mt-4">
                            <div wire:ignore id="map" class="h-64 w-full rounded-lg relative">
                                <!-- Map Placeholder -->
                                <div id="map-placeholder"
                                    class="absolute inset-0 bg-gray-100 dark:bg-gray-700 rounded-lg flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-12 w-12 text-gray-400 dark:text-gray-500 mb-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Loading map...</p>
                                </div>
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
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Tickets</h2>
                        @if ($event->isArchived())
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                                Archived
                            </span>
                        @endif
                    </div>

                    @if ($event->isArchived())
                        <div
                            class="mb-6 p-4 border-l-4 border-amber-500 bg-amber-50 dark:bg-amber-900/20 dark:border-amber-600 rounded-md">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-amber-500 dark:text-amber-400" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-amber-700 dark:text-amber-300">This event has been archived
                                        and is no longer available for booking.</p>
                                </div>
                            </div>
                        </div>
                    @endif

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
                        @if ($event->isArchived())
                            <button
                                class="w-full bg-gray-400 text-white py-3 rounded-md font-medium cursor-not-allowed"
                                disabled>
                                Event Archived
                            </button>
                        @else
                            <button wire:click="proceedToBooking"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-md font-medium transition-colors duration-300 {{ $totalTickets === 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                @if ($totalTickets === 0) disabled @endif>
                                Proceed to Checkout
                            </button>
                        @endif
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

@push('scripts')
    <!-- LocationIQ Map Scripts -->
    <link href="https://tiles.locationiq.com/v3/libs/maplibre-gl/1.15.2/maplibre-gl.css" rel="stylesheet" />
    <link href="https://tiles.locationiq.com/v3/libs/gl-geocoder/4.5.1/locationiq-gl-geocoder.css" rel="stylesheet" />
    <script src="https://tiles.locationiq.com/v3/libs/maplibre-gl/1.15.2/maplibre-gl.js"></script>
    <script src="https://tiles.locationiq.com/v3/js/liq-styles-ctrl-libre-gl.js?v=0.1.8"></script>
    <script src="https://tiles.locationiq.com/v3/libs/gl-geocoder/4.5.1/locationiq-gl-geocoder.min.js?v=0.2.3"></script>
    <script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.min.js"></script>

    <script>
        (function() {
            let map = null;
            let marker = null;
            let initializationAttempts = 0;
            const MAX_ATTEMPTS = 5;

            function initLocationIQ() {
                if (!window.locationiq) {
                    window.locationiq = {};
                }
            }

            function initializeMap() {
                // Check if map container exists
                const mapContainer = document.getElementById('map');
                const mapPlaceholder = document.getElementById('map-placeholder');

                // If map container doesn't exist, exit early
                if (!mapContainer) return;

                console.log('Initializing map in public event view');

                @if ($event->location)
                    // Clear existing map instance if it exists
                    if (map) {
                        map.remove();
                        map = null;
                    }

                    // Make sure LocationIQ and maplibregl are defined
                    if (!window.locationiq || !window.maplibregl) {
                        console.log('LocationIQ or maplibregl not loaded yet, initializing LocationIQ...');
                        initLocationIQ();

                        // Retry initialization if libraries aren't loaded yet
                        if (initializationAttempts < MAX_ATTEMPTS) {
                            initializationAttempts++;
                            console.log(
                                `Retrying map initialization (attempt ${initializationAttempts}/${MAX_ATTEMPTS})...`
                            );
                            setTimeout(initializeMap, 500);
                            return;
                        } else {
                            console.error('Failed to initialize map after multiple attempts');
                            if (mapPlaceholder) {
                                mapPlaceholder.style.display = 'flex';
                                const errorText = mapPlaceholder.querySelector('p');
                                if (errorText) {
                                    errorText.textContent = 'Unable to load map libraries';
                                }
                            }
                            return;
                        }
                    }

                    // Reset attempt counter on successful library load
                    initializationAttempts = 0;

                    try {
                        // Set LocationIQ key
                        locationiq.key = 'pk.8da423155473007977a90bb555d54b41';

                        map = new maplibregl.Map({
                            container: 'map',
                            style: locationiq.getLayer("Streets"),
                            zoom: 15,
                            center: [{{ $event->location->longitude }}, {{ $event->location->latitude }}]
                        });

                        // Add marker
                        marker = new maplibregl.Marker({
                                color: '#FF0000'
                            })
                            .setLngLat([{{ $event->location->longitude }}, {{ $event->location->latitude }}])
                            .addTo(map);

                        // Add popup
                        const popup = new maplibregl.Popup({
                                offset: 25
                            })
                            .setHTML('<strong>{{ $event->venue }}</strong>');

                        marker.setPopup(popup);

                        // Add navigation controls
                        map.addControl(new maplibregl.NavigationControl(), 'top-right');

                        // Hide placeholder when map is loaded
                        map.on('load', function() {
                            if (mapPlaceholder) {
                                mapPlaceholder.style.display = 'none';
                            }
                        });

                        // Show placeholder if map errors
                        map.on('error', function() {
                            if (mapPlaceholder) {
                                mapPlaceholder.style.display = 'flex';
                                const errorText = mapPlaceholder.querySelector('p');
                                if (errorText) {
                                    errorText.textContent = 'Unable to load map';
                                }
                            }
                        });

                    } catch (error) {
                        console.error('Error initializing map:', error);
                        if (mapPlaceholder) {
                            mapPlaceholder.style.display = 'flex';
                            const errorText = mapPlaceholder.querySelector('p');
                            if (errorText) {
                                errorText.textContent = 'Unable to load map';
                            }
                        }
                    }
                @endif
            }

            // Initialize on first load
            document.addEventListener('DOMContentLoaded', initializeMap);

            // Handle Livewire navigation events
            document.addEventListener('livewire:navigated', initializeMap);

            // Also listen for Alpine.js initialization events
            document.addEventListener('alpine:initialized', initializeMap);

            // We're not listening for Livewire updates to avoid unnecessary map reloads
            // The map should only be initialized once and when navigating to the page

            // Clean up map when navigating away
            document.addEventListener('livewire:navigating', () => {
                if (map) {
                    map.remove();
                    map = null;
                }
            });
        })();
    </script>
@endpush
