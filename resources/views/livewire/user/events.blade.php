<div class="bg-gray-100 dark:bg-gray-900 min-h-screen">
    <!-- Hero Section with Search Bar -->
    <div class="relative bg-cover bg-center h-64 md:h-96"
        style="background-image: url('https://images.unsplash.com/photo-1492684223066-81342ee5ff30?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80');">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative container mx-auto px-4 h-full flex flex-col justify-center">
            <h1 class="text-white text-3xl md:text-5xl font-bold mb-6">Find Your Next Event</h1>

            <!-- Search Form -->
            <div class="flex flex-col md:flex-row gap-2 md:gap-0 bg-white dark:bg-gray-800 p-2 rounded-lg shadow-lg">
                <div class="flex-1 md:border-r dark:border-gray-600">
                    <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Search Event"
                        class="w-full px-4 py-2 focus:outline-none dark:bg-gray-800 dark:text-white">
                </div>
                <div class="flex-1 md:border-r dark:border-gray-600">
                    <input type="text" wire:model.live.debounce.300ms="selectedLocation" placeholder="Enter Location"
                        class="w-full px-4 py-2 focus:outline-none dark:bg-gray-800 dark:text-white">
                </div>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium">
                    Find Events
                </button>
            </div>
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
                        {{ $selectedDate === $key ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Category Filters -->
        <div class="overflow-x-auto pb-4">
            <div class="flex space-x-2 min-w-max">
                @foreach ($categories as $key => $label)
                    <button wire:click="$set('selectedCategory', '{{ $key }}')"
                        class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap
                        {{ $selectedCategory === $key ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
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
                    class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                    <!-- Event Image -->
                    <div class="relative h-48 bg-gray-300 dark:bg-gray-700">
                        @if (!empty($event->banners) && is_array($event->banners) && count($event->banners) > 0)
                            <img src="{{ $event->banners[0] }}" alt="{{ $event->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-700">
                                <span class="text-gray-500 dark:text-gray-400">No Image</span>
                            </div>
                        @endif

                        <!-- Date Badge -->
                        <div
                            class="absolute top-4 left-4 bg-white dark:bg-gray-900 text-gray-800 dark:text-white px-3 py-1 rounded-md text-sm font-medium">
                            {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}
                        </div>
                    </div>

                    <!-- Event Content -->
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2 truncate">
                            {{ $event->name }}</h3>

                        <!-- Location -->
                        <div class="flex items-center text-gray-600 dark:text-gray-300 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="truncate">
                                @if ($event->event_type === 'Online Event')
                                    Online Event
                                @elseif($event->location)
                                    {{ $event->location->display_place ?? $event->venue }}
                                @else
                                    {{ $event->venue ?? 'Location TBD' }}
                                @endif
                            </span>
                        </div>

                        <!-- Organizer -->
                        <div class="flex items-center text-gray-600 dark:text-gray-300 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="truncate">{{ $event->organisation->name }}</span>
                        </div>

                        <!-- View Details Button -->
                        <a href="{{ route('user.event.detail', $event->id) }}"
                            class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-md font-medium transition-colors duration-300">
                            View Details
                        </a>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full py-12 flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
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
