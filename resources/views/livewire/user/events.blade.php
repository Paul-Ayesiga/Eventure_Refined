<div>
    <!-- Hero Section with Search Bar -->
    <div class="relative bg-cover bg-center h-64 md:h-96"
        style="background-image: url('https://images.unsplash.com/photo-1492684223066-81342ee5ff30?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80');">
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="relative container mx-auto px-4 h-full flex flex-col justify-center">
            <h1 class="text-white text-3xl md:text-5xl font-bold mb-6">Find Your Next Event</h1>

            <!-- Search Form -->
            <form wire:submit.prevent
                class="flex flex-col md:flex-row gap-2 md:gap-0 bg-white dark:bg-gray-800 p-2 rounded-lg shadow-lg">
                <div class="flex-1 md:border-r dark:border-gray-600">
                    <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Search Event"
                        class="w-full px-4 py-2 focus:outline-none dark:bg-gray-800 dark:text-white">
                </div>
                <div class="flex-1 md:border-r dark:border-gray-600">
                    <input type="text" wire:model.live.debounce.300ms="selectedLocation" placeholder="Enter Location"
                        class="w-full px-4 py-2 focus:outline-none dark:bg-gray-800 dark:text-white">
                </div>
                <button type="submit"
                    class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-md font-medium">
                    Find Events
                </button>
            </form>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="container mx-auto px-4 py-6">
        <!-- Date Filters -->
        <div class="overflow-x-auto pb-4">
            <div class="flex space-x-2 min-w-max">
                @foreach ($dateFilters as $key => $label)
                    <button wire:click="$set('selectedDate', '{{ $key }}')"
                        class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap
                        {{ $selectedDate === $key ? 'bg-teal-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Category Filters -->
        <div class="overflow-x-auto pb-4">
            <div class="flex space-x-2 min-w-max">
                @foreach ($categoryOptions as $key => $label)
                    <button wire:click="$set('selectedCategory', '{{ $key }}')"
                        class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap
                        {{ $selectedCategory === $key ? 'bg-teal-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Events Grid -->
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($events as $event)
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <!-- Event Image -->
                    <a href="{{ route('user.event.detail', $event->id) }}" class="block">
                        <div class="relative h-48 bg-gray-300 dark:bg-gray-700">
                            @if ($event->banner)
                                <img src="{{ $event->banner }}" alt="{{ $event->name }}"
                                    class="w-full h-full object-cover"
                                    onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSJjdXJyZW50Q29sb3IiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj48cGF0aCBkPSJNNCAxNmw0LjU4Ni00LjU4NmEyIDIgMCAwMTIuODI4IDBMMTYgMTZtLTItMmwxLjU4Ni0xLjU4NmEyIDIgMCAwMTIuODI4IDBMMjAgMTRtLTYtNmguMDFNNiAyMGgxMmEyIDIgMCAwMDItMlY2YTIgMiAwIDAwLTItMkg2YTIgMiAwIDAwLTIgMnYxMmEyIDIgMCAwMDIgMnoiLz48L3N2Zz4='"
                                @else <div
                                    class="w-full h-full flex items-center justify-center bg-gradient-to-r from-teal-500 to-blue-600">
                                <span class="text-white text-lg font-bold">{{ $event->name }}</span>
                        </div>
            @endif

            <!-- Date Badge -->
            <div
                class="absolute top-4 left-4 bg-white dark:bg-gray-900 text-gray-800 dark:text-white px-3 py-1 rounded-md text-sm font-medium">
                {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}
            </div>

            <!-- Category Badge -->
            @if ($event->category)
                <div class="absolute top-4 right-4 bg-teal-600 text-white px-3 py-1 rounded-full text-xs font-medium">
                    {{ $event->category }}
                </div>
            @endif
        </div>
        </a>

        <!-- Event Content -->
        <div class="p-4">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2 truncate">
                <a href="{{ route('user.event.detail', $event->id) }}"
                    class="hover:text-teal-600 dark:hover:text-teal-400">
                    {{ $event->name }}
                </a>
            </h3>

            <!-- Location -->
            <div class="flex items-center text-gray-600 dark:text-gray-300 mb-2">
                @if ($event->event_type === 'Online Event')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="truncate">Online Event</span>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="truncate">
                        @if ($event->location)
                            {{ $event->location->display_place ?? $event->venue }}
                        @else
                            {{ $event->venue ?? 'Location TBD' }}
                        @endif
                    </span>
                @endif
            </div>

            <!-- Organizer -->
            <div class="flex items-center text-gray-600 dark:text-gray-300 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span class="truncate">{{ $event->organisation->name }}</span>
            </div>

            <!-- Price and View Details -->
            <div class="flex justify-between items-center">
                <span class="text-teal-600 dark:text-teal-400 font-bold">
                    @php
                        $lowestPrice = $event->tickets->min('price');
                        $currency = $event->currency ?? 'USD';
                    @endphp

                    @if ($lowestPrice > 0)
                        {{ $currency }} {{ number_format($lowestPrice, 2) }}
                    @else
                        Free
                    @endif
                </span>

                <a href="{{ route('user.event.detail', $event->id) }}"
                    class="bg-teal-100 hover:bg-teal-200 text-teal-800 text-sm font-medium px-3 py-1 rounded transition-colors duration-300">
                    View Details
                </a>
            </div>
        </div>
    </div>
@empty
    <div class="col-span-full py-12 flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="text-xl font-medium mb-1">No Events Found</h3>
        <p class="text-center">We couldn't find any events matching your criteria. Try adjusting your
            filters.</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-8">
    {{ $events->links() }}
</div>
</div>
</div>
