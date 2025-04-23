<div>
    <!-- Gradient background strip -->
    <div
        class="absolute top-0 left-0 right-0 w-full h-32 bg-gradient-to-r from-cyan-100 to-purple-100 dark:from-cyan-400 dark:to-blue-700">
    </div>
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
                    {{ __('Good ' . (date('H') < 12 ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening')) . ', ' . auth()->user()->name) }}!
                    👋
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    {{ __("Welcome to your dashboard. Here's an overview of your events and statistics.") }}
                </p>
            </div>
        </div>

        <!-- Date range navigation bar -->
        <div
            class="flex flex-col sm:flex-row items-center justify-between gap-3 rounded-xl border border-gray-200 dark:border-gray-700 p-3 bg-white dark:bg-gray-800">
            <div class="flex items-center space-x-2 w-full sm:w-auto">
                <button wire:click="previousPeriod" class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                    <!-- Left Arrow Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button wire:click="nextPeriod" class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                    <!-- Right Arrow Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                <span class="text-sm font-medium text-gray-900 dark:text-gray-200 truncate">
                    {{ $startDate->format('jS M, Y') }} - {{ $endDate->format('jS M, Y') }}
                </span>
            </div>
            <div class="relative w-full sm:w-auto">
                <flux:dropdown class="w-full sm:w-auto">
                    <flux:button icon:trailing="chevron-down" class="w-full sm:w-auto text-gray-900 dark:text-gray-200">
                        {{ __('Selected Events') }} ({{ count($selectedEvents) }})
                    </flux:button>
                    <flux:menu>
                        @forelse($availableEvents as $event)
                            <flux:menu.item wire:click="toggleEvent({{ $event->id }})">
                                {{ $event->name }}
                            </flux:menu.item>
                        @empty
                            <flux:menu.item>
                                {{ __('No events available') }}
                            </flux:menu.item>
                        @endforelse
                    </flux:menu>
                </flux:dropdown>
            </div>
        </div>

        <!-- Statistic Cards -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-2">
            <!-- Revenue Card -->
            <div
                class="bg-white dark:bg-gray-800 relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex flex-col">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1 uppercase font-medium">
                        {{ __('Revenue') }}
                    </p>
                    <div class="flex items-center">
                        <div class="mr-4 flex-shrink-0">
                            <!-- Revenue Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $currency }} {{ number_format($statistics['revenue']['current'], 2) }}
                            </h3>
                            <p class="text-sm flex items-center mt-1">
                                <span
                                    class="{{ $statistics['revenue']['change'] >= 0 ? 'text-green-500' : 'text-red-500' }} flex items-center">
                                    <!-- Up or down arrow icon can be adjusted based on change -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="{{ $statistics['revenue']['change'] >= 0 ? 'M5 10l7-7 7 7' : 'M19 14l-7 7-7-7' }}" />
                                    </svg>
                                    {{ abs($statistics['revenue']['change']) }}%
                                </span>
                                <span class="text-gray-500 dark:text-gray-400 ml-1">{{ __('vs last period') }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Bookings Card -->
            <div
                class="bg-white dark:bg-gray-800 relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex flex-col">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1 uppercase font-medium">
                        {{ __('Bookings') }}
                    </p>
                    <div class="flex items-center">
                        <div class="mr-4 flex-shrink-0">
                            <!-- Bookings Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $statistics['bookings']['current'] }}
                            </h3>
                            <p class="text-sm flex items-center mt-1">
                                <span
                                    class="{{ $statistics['bookings']['change'] >= 0 ? 'text-green-500' : 'text-red-500' }} flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="{{ $statistics['bookings']['change'] >= 0 ? 'M5 10l7-7 7 7' : 'M19 14l-7 7-7-7' }}" />
                                    </svg>
                                    {{ abs($statistics['bookings']['change']) }}%
                                </span>
                                <span class="text-gray-500 dark:text-gray-400 ml-1">{{ __('vs last period') }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Page Views Card -->
            <div
                class="bg-white dark:bg-gray-800 relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex flex-col">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1 uppercase font-medium">
                        {{ __('Page Views') }}
                    </p>
                    <div class="flex items-center">
                        <div class="mr-4 flex-shrink-0">
                            <!-- Page Views Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $statistics['page_views']['current'] }}
                            </h3>
                            <p class="text-sm flex items-center mt-1">
                                <span
                                    class="{{ $statistics['page_views']['change'] >= 0 ? 'text-green-500' : 'text-red-500' }} flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="{{ $statistics['page_views']['change'] >= 0 ? 'M5 10l7-7 7 7' : 'M19 14l-7 7-7-7' }}" />
                                    </svg>
                                    {{ abs($statistics['page_views']['change']) }}%
                                </span>
                                <span class="text-gray-500 dark:text-gray-400 ml-1">{{ __('vs last period') }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tickets Sold Card -->
            <div
                class="bg-white dark:bg-gray-800 relative overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex flex-col">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1 uppercase font-medium">
                        {{ __('Tickets Sold') }}
                    </p>
                    <div class="flex items-center">
                        <div class="mr-4 flex-shrink-0">
                            <!-- Tickets Sold Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $statistics['tickets_sold']['current'] }}
                            </h3>
                            <p class="text-sm flex items-center mt-1">
                                <span
                                    class="{{ $statistics['tickets_sold']['change'] >= 0 ? 'text-green-500' : 'text-red-500' }} flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="{{ $statistics['tickets_sold']['change'] >= 0 ? 'M5 10l7-7 7 7' : 'M19 14l-7 7-7-7' }}" />
                                    </svg>
                                    {{ abs($statistics['tickets_sold']['change']) }}%
                                </span>
                                <span class="text-gray-500 dark:text-gray-400 ml-1">{{ __('vs last period') }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div
            class="bg-white dark:bg-gray-800 relative h-full flex-1 overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
            <div
                class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div class="w-full sm:w-auto">
                    <flux:dropdown>
                        <flux:button icon:trailing="chevron-down" class="text-gray-900 dark:text-gray-200">
                            {{ $sortBy }}
                        </flux:button>
                        <flux:menu>
                            <flux:menu.item icon="ticket" wire:click="$set('sortBy', 'tickets')"
                                :active="$sortBy === 'tickets'">
                                {{ __('Tickets Sold') }}
                            </flux:menu.item>
                            <flux:menu.item icon="currency-dollar" wire:click="$set('sortBy', 'revenue')"
                                :active="$sortBy === 'revenue'">
                                {{ __('Revenue') }}
                            </flux:menu.item>
                            <flux:menu.item icon="shopping-bag" wire:click="$set('sortBy', 'bookings')"
                                :active="$sortBy === 'bookings'">
                                {{ __('Bookings') }}
                            </flux:menu.item>
                            <flux:menu.item icon="eye" wire:click="$set('sortBy', 'page_views')"
                                :active="$sortBy === 'page_views'">
                                {{ __('Page Views') }}
                            </flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </div>

                <div class="w-full sm:w-auto flex">
                    <div class="relative tab-group w-full sm:w-auto grid grid-cols-3 sm:flex overflow-x-auto">
                        <button wire:click="setDateRange('daily')"
                            class="relative px-3 sm:px-5 py-2 sm:py-2.5 text-sm font-medium {{ $dateRange === 'daily' ? 'text-blue-600' : 'text-gray-600 dark:text-gray-400' }} hover:text-blue-600 dark:hover:text-blue-400 before:absolute before:bottom-0 before:left-0 before:h-{{ $dateRange === 'daily' ? '1' : '0' }} before:w-full before:rounded-t-full before:bg-blue-600 dark:before:bg-blue-400 transition-colors duration-200 text-center">
                            {{ __('Daily') }}
                        </button>
                        <button wire:click="setDateRange('weekly')"
                            class="relative px-3 sm:px-5 py-2 sm:py-2.5 text-sm font-medium {{ $dateRange === 'weekly' ? 'text-blue-600' : 'text-gray-600 dark:text-gray-400' }} hover:text-blue-600 dark:hover:text-blue-400 before:absolute before:bottom-0 before:left-0 before:h-{{ $dateRange === 'weekly' ? '1' : '0' }} before:w-full before:rounded-t-full before:bg-blue-600 dark:before:bg-blue-400 transition-all duration-200 text-center">
                            {{ __('Weekly') }}
                        </button>
                        <button wire:click="setDateRange('monthly')"
                            class="relative px-3 sm:px-5 py-2 sm:py-2.5 text-sm font-medium {{ $dateRange === 'monthly' ? 'text-blue-600' : 'text-gray-600 dark:text-gray-400' }} hover:text-blue-600 dark:hover:text-blue-400 before:absolute before:bottom-0 before:left-0 before:h-{{ $dateRange === 'monthly' ? '1' : '0' }} before:w-full before:rounded-t-full before:bg-blue-600 dark:before:bg-blue-400 transition-all duration-200 text-center">
                            {{ __('Monthly') }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-4">
                @if (isset($chartData) && !empty($chartData['labels']))
                    <!-- Chart Integration with Alpine.js for reactivity -->
                    <div wire:ignore x-data="{
                        chart: null,
                        initChart() {
                            if (this.chart) {
                                this.chart.destroy();
                            }
                    
                            // Make sure chartData is defined
                            if (!$wire.chartData || !$wire.chartData.labels) {
                                console.error('Chart data is not available yet');
                                return;
                            }
                    
                            // Create a clean copy of the data to avoid reference issues
                            const chartData = JSON.parse(JSON.stringify($wire.chartData));
                            console.log('Chart data:', chartData);
                    
                            // Wait for the DOM to be fully updated
                            setTimeout(() => {
                                const ctx = document.getElementById('dashboardChart')?.getContext('2d');
                                if (!ctx) {
                                    console.error('Canvas context not available');
                                    return;
                                }
                    
                                this.chart = new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: chartData.labels,
                                        datasets: [{
                                                label: '{{ __('Bookings') }}',
                                                data: chartData.datasets?.bookings || [],
                                                borderColor: 'rgba(59,130,246,1)',
                                                backgroundColor: 'rgba(59,130,246,0.1)',
                                                tension: 0.1,
                                                fill: true
                                            },
                                            {
                                                label: '{{ __('Revenue') }} ({{ $currency }})',
                                                data: chartData.datasets?.revenue || [],
                                                borderColor: 'rgba(16,185,129,1)',
                                                backgroundColor: 'rgba(16,185,129,0.1)',
                                                tension: 0.1,
                                                fill: true
                                            }
                                        ]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            tooltip: {
                                                mode: 'index',
                                                intersect: false,
                                                callbacks: {
                                                    label: function(context) {
                                                        let label = context.dataset.label || '';
                                                        if (label) {
                                                            label += ': ';
                                                        }
                                                        if (context.parsed.y !== null) {
                                                            if (context.dataset.label.includes('Revenue')) {
                                                                label += '{{ $currency }} ' + context.parsed.y.toFixed(2);
                                                            } else {
                                                                label += context.parsed.y;
                                                            }
                                                        }
                                                        return label;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                });
                            }, 100);
                        }
                    }" x-init="initChart()"
                        @chartDataUpdated.window="initChart()" x-effect="$watch('$wire.chartData', () => initChart())"
                        x-effect="$watch('$wire.selectedEvents', () => initChart())">
                        <canvas id="dashboardChart"></canvas>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-64 text-gray-400 dark:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-3" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <p class="text-lg font-medium text-gray-900 dark:text-gray-200">{{ __('No data available') }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Select different criteria or time period to view data') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush
