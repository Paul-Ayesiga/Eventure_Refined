<x-layouts.organisation :title="__('Dashboard')">
        <!-- Gradient background strip -->
    <div class="absolute top-0 left-0 right-0 w-full h-32 bg-gradient-to-r from-cyan-100 to-purple-100 dark:from-gray-800 dark:to-gray-900"></div>
    <div class="relative flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
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

        <!-- Hero Section with Greeting -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-4">
            <div class="max-w-4xl">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                    {{ __('Good ' . (date('H') < 12 ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening')) . ', ' . auth()->user()->name) }}! 👋
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    {{ __("Welcome to your dashboard. Here's an overview of your events and statistics.") }}
                </p>
            </div>
        </div>

        <!-- Date range navigation bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 rounded-xl border border-gray-200 dark:border-gray-700 p-3 bg-white dark:bg-gray-800">
            <div class="flex items-center space-x-2 w-full sm:w-auto">
                <button class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                <span class="text-sm font-medium text-gray-900 dark:text-gray-200 truncate">
                    {{ \Carbon\Carbon::now()->startOfMonth()->format('jS M, Y') }} - {{ \Carbon\Carbon::now()->format('jS M, Y') }}
                </span>
            </div>
            <div class="relative w-full sm:w-auto">
                <flux:dropdown class="w-full sm:w-auto">
                    <flux:button icon:trailing="chevron-down" class="w-full sm:w-auto text-gray-900 dark:text-gray-200">Selected Events (0)</flux:button>
                    <flux:menu>
                        <flux:menu.item>No events selected</flux:menu.item>
                    </flux:menu>
                </flux:dropdown>
            </div>
        </div>

        <div class="grid auto-rows-min gap-4 md:grid-cols-2">
            <div class="bg-white dark:bg-gray-800 relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex flex-col">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1 uppercase font-medium">Revenue</p>
                    <div class="flex items-center">
                        <div class="mr-4 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">AUD $0.00</h3>
                            <p class="text-sm flex items-center mt-1">
                                <span class="text-green-500 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                    </svg>
                                    2.5%
                                </span>
                                <span class="text-gray-500 dark:text-gray-400 ml-1">vs last period</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex flex-col">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1 uppercase font-medium">Orders</p>
                    <div class="flex items-center">
                        <div class="mr-4 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">0</h3>
                            <p class="text-sm flex items-center mt-1">
                                <span class="text-red-500 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                    </svg>
                                    1.8%
                                </span>
                                <span class="text-gray-500 dark:text-gray-400 ml-1">vs last period</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex flex-col">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1 uppercase font-medium">Page Views</p>
                    <div class="flex items-center">
                        <div class="mr-4 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">0</h3>
                            <p class="text-sm flex items-center mt-1">
                                <span class="text-green-500 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                    </svg>
                                    4.3%
                                </span>
                                <span class="text-gray-500 dark:text-gray-400 ml-1">vs last period</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex flex-col">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1 uppercase font-medium">Tickets Sold</p>
                    <div class="flex items-center">
                        <div class="mr-4 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">0</h3>
                            <p class="text-sm flex items-center mt-1">
                                <span class="text-green-500 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                    </svg>
                                    3.2%
                                </span>
                                <span class="text-gray-500 dark:text-gray-400 ml-1">vs last period</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 relative h-full flex-1 overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div class="w-full sm:w-auto">
                    <flux:dropdown>
                        <flux:button icon:trailing="chevron-down" class="text-gray-900 dark:text-gray-200">Sort By </flux:button>
                        <flux:menu>
                            <flux:menu.item icon="ticket" default>Tickets Sold</flux:menu.item>
                            <flux:menu.item icon="currency-dollar">Revenue</flux:menu.item>
                            <flux:menu.item icon="shopping-bag">Orders</flux:menu.item>
                            <flux:menu.item icon="eye">Page Views</flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </div>

                <div class="w-full sm:w-auto flex">
                    <div class="relative tab-group w-full sm:w-auto grid grid-cols-3 sm:flex overflow-x-auto">
                        <button class="relative px-3 sm:px-5 py-2 sm:py-2.5 text-sm font-medium text-blue-600 dark:text-blue-400 before:absolute before:bottom-0 before:left-0 before:h-1 before:w-full before:rounded-t-full before:bg-blue-600 dark:before:bg-blue-400 transition-colors duration-200 text-center">
                            Daily
                        </button>
                        <button class="relative px-3 sm:px-5 py-2 sm:py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 before:absolute before:bottom-0 before:left-0 before:h-0 hover:before:h-1 before:w-full before:rounded-t-full before:bg-blue-600 dark:before:bg-blue-400 transition-all duration-200 text-center">
                            Weekly
                        </button>
                        <button class="relative px-3 sm:px-5 py-2 sm:py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 before:absolute before:bottom-0 before:left-0 before:h-0 hover:before:h-1 before:w-full before:rounded-t-full before:bg-blue-600 dark:before:bg-blue-400 transition-all duration-200 text-center">
                            Monthly
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-4">
                <div class="flex flex-col items-center justify-center h-64 text-gray-400 dark:text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <p class="text-lg font-medium text-gray-900 dark:text-gray-200">No data available</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Select different criteria or time period to view data</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.organisation>
