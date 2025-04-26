<div>
    <!-- Hero Section -->
    <section class="relative py-24 overflow-hidden">
        <!-- Stylish gradient background with design elements -->
        <div class="absolute inset-0 bg-gradient-to-br from-teal-600 via-blue-500 to-purple-600"></div>

        <!-- Decorative elements -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-40 h-40 rounded-full bg-white"></div>
            <div class="absolute bottom-10 right-10 w-60 h-60 rounded-full bg-white"></div>
            <div class="absolute top-1/4 right-1/4 w-20 h-20 rounded-full bg-white"></div>
            <div class="absolute bottom-1/3 left-1/3 w-32 h-32 rounded-full bg-white"></div>
        </div>

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black opacity-20"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 drop-shadow-lg">Discover Amazing Events Near
                    You</h1>
                <p class="text-xl text-white mb-8 drop-shadow">Find and book tickets for concerts, workshops,
                    conferences, and more.
                </p>

                <!-- Search Form -->
                <form wire:submit.prevent="search" class="flex flex-col md:flex-row gap-4 max-w-2xl mx-auto">
                    <input type="text" wire:model="searchQuery" placeholder="Search for events..."
                        class="flex-1 px-6 py-4 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-teal-500 shadow-lg">
                    <button type="submit"
                        class="bg-teal-600 hover:bg-teal-700 text-white px-8 py-4 rounded-lg font-medium transition-colors duration-300 shadow-lg">
                        Search
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Featured Events Section -->
    <section class="py-20">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white relative">
                    Featured Events
                    <span class="absolute -bottom-2 left-0 w-20 h-1 bg-gradient-to-r from-teal-500 to-blue-600"></span>
                </h2>
                <a href="{{ route('user.events') }}"
                    class="text-teal-600 dark:text-teal-400 hover:underline flex items-center">
                    View All
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </div>

            @if ($featuredEvents->isEmpty())
                <div class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-4 text-xl font-medium text-gray-800 dark:text-white">No Featured Events</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Check back soon for exciting featured events!</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 mx-2">
                    @foreach ($featuredEvents as $event)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition-transform duration-300 hover:shadow-xl hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
                            <a href="{{ route('user.event.detail', $event->id) }}">
                                <div class="h-48 bg-gray-300 dark:bg-gray-700 relative">
                                    @if ($event->getBannerAttribute())
                                        <img src="{{ $event->getBannerAttribute() }}" alt="{{ $event->name }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div
                                            class="flex items-center justify-center h-full bg-gradient-to-r from-teal-500 to-blue-600">
                                            <span class="text-white text-xl font-bold">{{ $event->name }}</span>
                                        </div>
                                    @endif

                                    @if ($event->category)
                                        <span
                                            class="absolute top-4 right-4 bg-teal-600 text-white text-xs font-bold px-3 py-1 rounded-full">
                                            {{ $event->category }}
                                        </span>
                                    @endif
                                </div>
                            </a>

                            <div class="p-6">
                                <div class="flex items-center text-gray-500 dark:text-gray-400 text-sm mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}

                                    @if ($event->event_type === 'Online Event')
                                        <span class="ml-4 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            Online
                                        </span>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
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

                                <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">
                                    <a href="{{ route('user.event.detail', $event->id) }}"
                                        class="hover:text-teal-600 dark:hover:text-teal-400">
                                        {{ $event->name }}
                                    </a>
                                </h3>

                                <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                    {{ $event->description }}
                                </p>

                                <div class="flex justify-between items-center">
                                    <span class="text-teal-600 dark:text-teal-400 font-bold">
                                        @php
                                            $lowestPrice = $event->tickets->min('price');
                                            $currency = $event->currency ?? 'USD';
                                        @endphp

                                        @if ($lowestPrice > 0)
                                            From {{ $currency }} {{ number_format($lowestPrice, 2) }}
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
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-20 relative overflow-hidden">
        <!-- Stylish gradient background -->
        <div class="absolute inset-0 bg-gradient-to-bl from-gray-100 to-teal-50 dark:from-gray-800 dark:to-gray-900">
        </div>

        <!-- Decorative patterns -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute inset-0"
                style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23000000\' fill-opacity=\'0.2\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
            </div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white text-center mb-12 relative inline-block">
                Browse by Category
                <span
                    class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-gradient-to-r from-teal-500 to-blue-600"></span>
            </h2>

            @if (empty($categories))
                <div class="text-center py-8">
                    <p class="text-gray-600 dark:text-gray-400">No categories available at the moment.</p>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 mx-2">
                    @foreach ($categories as $category)
                        <a href="{{ route('user.events', ['selectedCategory' => $category]) }}"
                            class="bg-white dark:bg-gray-700 rounded-lg shadow-md p-6 text-center transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                            <div
                                class="bg-teal-100 dark:bg-teal-900 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-8 w-8 text-teal-600 dark:text-teal-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-800 dark:text-white">{{ $category }}</h3>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Upcoming Events Section -->
    <section class="py-20 relative">
        <!-- Subtle background pattern -->
        <div
            class="absolute inset-0 bg-gradient-to-tr from-white to-teal-50 dark:from-gray-900 dark:to-gray-800 opacity-50">
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="flex justify-between items-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white relative">
                    Upcoming Events
                    <span class="absolute -bottom-2 left-0 w-20 h-1 bg-gradient-to-r from-teal-500 to-blue-600"></span>
                </h2>
                <a href="{{ route('user.events') }}"
                    class="text-teal-600 dark:text-teal-400 hover:underline flex items-center">
                    View All
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </div>

            @if ($upcomingEvents->isEmpty())
                <div class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-4 text-xl font-medium text-gray-800 dark:text-white">No Upcoming Events</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Check back soon for exciting upcoming events!</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mx-2">
                    @foreach ($upcomingEvents as $event)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition-transform duration-300 hover:shadow-xl hover:-translate-y-2 border border-gray-100 dark:border-gray-700">
                            <a href="{{ route('user.event.detail', $event->id) }}">
                                <div class="h-40 bg-gray-300 dark:bg-gray-700 relative">
                                    @if ($event->banner)
                                        <img src="{{ $event->banner }}" alt="{{ $event->name }}"
                                            class="w-full h-full object-cover"
                                            onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSJjdXJyZW50Q29sb3IiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIj48cGF0aCBkPSJNNCAxNmw0LjU4Ni00LjU4NmEyIDIgMCAwMTIuODI4IDBMMTYgMTZtLTItMmwxLjU4Ni0xLjU4NmEyIDIgMCAwMTIuODI4IDBMMjAgMTRtLTYtNmguMDFNNiAyMGgxMmEyIDIgMCAwMDItMlY2YTIgMiAwIDAwLTItMkg2YTIgMiAwIDAwLTIgMnYxMmEyIDIgMCAwMDIgMnoiLz48L3N2Zz4='"
                                        @else <div
                                            class="flex items-center justify-center h-full bg-gradient-to-r from-teal-500 to-blue-600">
                                        <span class="text-white text-lg font-bold">{{ $event->name }}</span>
                                </div>
                    @endif
                </div>
                </a>

                <div class="p-4">
                    <div class="flex items-center text-gray-500 dark:text-gray-400 text-xs mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}
                    </div>

                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2 line-clamp-1">
                        <a href="{{ route('user.event.detail', $event->id) }}"
                            class="hover:text-teal-600 dark:hover:text-teal-400">
                            {{ $event->name }}
                        </a>
                    </h3>

                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-2">
                        {{ $event->description }}
                    </p>

                    <a href="{{ route('user.event.detail', $event->id) }}"
                        class="text-teal-600 dark:text-teal-400 text-sm font-medium hover:underline">
                        View Details
                    </a>
                </div>
        </div>
        @endforeach
</div>
@endif
</div>
</section>

<!-- CTA Section -->
<section class="py-20 relative overflow-hidden">
    <!-- Enhanced gradient background -->
    <div class="absolute inset-0 bg-gradient-to-br from-teal-600 via-blue-500 to-purple-600"></div>

    <!-- Decorative elements -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-full h-full"
            style="background-image: url('data:image/svg+xml,%3Csvg width=\'52\' height=\'26\' viewBox=\'0 0 52 26\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M10 10c0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6h2c0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4v2c-3.314 0-6-2.686-6-6 0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6zm25.464-1.95l8.486 8.486-1.414 1.414-8.486-8.486 1.414-1.414z\' /%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
        </div>
    </div>

    <!-- Overlay -->
    <div class="absolute inset-0 bg-black opacity-30"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 drop-shadow-lg">Ready to Host Your Own Event?
            </h2>
            <p class="text-xl text-white mb-8 drop-shadow">Join thousands of event organizers who trust Eventure to
                manage
                their events.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('create-organisation') }}"
                    class="bg-white text-teal-600 hover:bg-gray-100 px-8 py-4 rounded-lg font-medium transition-colors duration-300 shadow-lg">
                    Create Organization
                </a>
                <a href="{{ route('user.events') }}"
                    class="bg-transparent border-2 border-white text-white hover:bg-white/10 px-8 py-4 rounded-lg font-medium transition-colors duration-300 shadow-lg">
                    Explore Events
                </a>
            </div>
        </div>
    </div>
</section>
</div>
