<x-layouts.user :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:breadcrumbs>
            @php
                $breadcrumbs = getBreadcrumbs();
            @endphp
            @foreach ($breadcrumbs as $index => $crumb)
                @if ($index < count($breadcrumbs) - 1)
                    <flux:breadcrumbs.item href="{{ $crumb['url'] }}">
                        {{ $crumb['title'] }}
                    </flux:breadcrumbs.item>
                @else
                    <flux:breadcrumbs.item>
                        {{ $crumb['title'] }}
                    </flux:breadcrumbs.item>
                @endif
            @endforeach
        </flux:breadcrumbs>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- User Dashboard Content -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Welcome to Your Dashboard</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-4">Manage your account and view your event bookings.</p>

                <!-- Dashboard actions -->
                <div class="mt-6 space-y-4">
                    <a href="{{ route('user.events') }}"
                        class="flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        <flux:icon name="calendar" class="w-5 h-5 mr-2" />
                        Browse Events
                    </a>
                    <a href="{{ route('user.bookings') }}"
                        class="flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        <flux:icon name="ticket" class="w-5 h-5 mr-2" />
                        View My Bookings
                    </a>
                    <a href="{{ route('usr.settings.profile') }}"
                        class="flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        <flux:icon name="user" class="w-5 h-5 mr-2" />
                        Update Profile
                    </a>
                </div>
            </div>

            <!-- Become an Organizer Card -->
            <div class="bg-gradient-to-r from-teal-500 to-blue-500 p-6 rounded-lg shadow-md text-white">
                <h2 class="text-xl font-semibold mb-4">Become an Event Organizer</h2>
                <p class="mb-6">Create your own events, sell tickets, and manage attendees by becoming an organizer.
                </p>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <flux:icon name="check-circle" class="w-5 h-5 mr-2" />
                        Create and manage events
                    </div>
                    <div class="flex items-center">
                        <flux:icon name="check-circle" class="w-5 h-5 mr-2" />
                        Sell tickets online
                    </div>
                    <div class="flex items-center">
                        <flux:icon name="check-circle" class="w-5 h-5 mr-2" />
                        Track attendees and bookings
                    </div>
                </div>
                <div class="mt-8">
                    <a href="{{ route('create-organisation') }}"
                        class="inline-flex items-center px-4 py-2 bg-white text-blue-600 rounded-md font-medium hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <flux:icon name="building-office-2" class="w-5 h-5 mr-2" />
                        Create Organization
                    </a>
                </div>
            </div>
        </div>

        @if (auth()->user()->organisations()->count() > 0)
            <div class="mt-8 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Your Organizations</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach (auth()->user()->organisations as $organisation)
                        <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center mb-3">
                                @if ($organisation->logo_url)
                                    <img src="{{ Storage::url($organisation->logo_url) }}"
                                        alt="{{ $organisation->name }}" class="w-10 h-10 rounded-full mr-3">
                                @else
                                    <div
                                        class="w-10 h-10 rounded-full bg-teal-500 flex items-center justify-center text-white mr-3">
                                        {{ substr($organisation->name, 0, 1) }}
                                    </div>
                                @endif
                                <h3 class="font-medium">{{ $organisation->name }}</h3>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $organisation->email }}</p>
                            <a href="{{ route('organisation-dashboard', ['organisationId' => $organisation->id]) }}"
                                class="inline-flex items-center text-sm text-teal-600 hover:text-teal-800">
                                <flux:icon name="arrow-right-circle" class="w-4 h-4 mr-1" />
                                Go to Organisation Dashboard
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.user>
