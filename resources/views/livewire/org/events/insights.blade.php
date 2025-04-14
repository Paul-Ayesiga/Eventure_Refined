<div>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <!-- Period Selector -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Event Insights</h2>
            <div class="flex flex-wrap gap-2">
                <flux:button.group>
                    <flux:button wire:click="$set('selectedPeriod', '7d')"
                        variant="{{ $selectedPeriod === '7d' ? 'primary' : 'outline' }}" class="whitespace-nowrap">
                        <span class="hidden sm:inline">Last 7 Days</span>
                        <span class="sm:hidden">7D</span>
                    </flux:button>
                    <flux:button wire:click="$set('selectedPeriod', '30d')"
                        variant="{{ $selectedPeriod === '30d' ? 'primary' : 'outline' }}" class="whitespace-nowrap">
                        <span class="hidden sm:inline">Last 30 Days</span>
                        <span class="sm:hidden">30D</span>
                    </flux:button>
                    <flux:button wire:click="$set('selectedPeriod', '90d')"
                        variant="{{ $selectedPeriod === '90d' ? 'primary' : 'outline' }}" class="whitespace-nowrap">
                        <span class="hidden sm:inline">Last 90 Days</span>
                        <span class="sm:hidden">90D</span>
                    </flux:button>
                    <flux:button wire:click="$set('selectedPeriod', 'all')"
                        variant="{{ $selectedPeriod === 'all' ? 'primary' : 'outline' }}" class="whitespace-nowrap">
                        <span class="hidden sm:inline">All Time</span>
                        <span class="sm:hidden">All</span>
                    </flux:button>
                </flux:button.group>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <!-- Total Bookings -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                        <flux:icon name="ticket" class="h-6 w-6 text-blue-600 dark:text-blue-300" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Bookings</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_bookings'] }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                        <flux:icon name="currency-dollar" class="h-6 w-6 text-green-600 dark:text-green-300" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Revenue</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ number_format($stats['total_revenue'], 2) }} {{ $event->currency }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tickets Sold -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                        <flux:icon name="users" class="h-6 w-6 text-purple-600 dark:text-purple-300" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tickets Sold</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ $stats['total_tickets_sold'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Average Ticket Price -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                        <flux:icon name="tag" class="h-6 w-6 text-yellow-600 dark:text-yellow-300" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Avg. Ticket Price</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ number_format($stats['average_ticket_price'], 2) }} {{ $event->currency }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Conversion Rate -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 dark:bg-red-900">
                        <flux:icon name="arrow-trending-up" class="h-6 w-6 text-red-600 dark:text-red-300" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Conversion Rate</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ $stats['conversion_rate'] }}%</p>
                    </div>
                </div>
            </div>

            <!-- Top Selling Ticket -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-900">
                        <flux:icon name="star" class="h-6 w-6 text-indigo-600 dark:text-indigo-300" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Top Selling Ticket</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $stats['top_selling_ticket']?->name ?? 'N/A' }}
                        </p>
                        @if ($stats['top_selling_ticket'])
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $stats['top_selling_ticket']->quantity_sold }} sold
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-data>
            <!-- Bookings Chart -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    Bookings
                    @switch($selectedPeriod)
                        @case('7d')
                            Last 7 Days
                        @break

                        @case('30d')
                            Last 30 Days
                        @break

                        @case('90d')
                            Last 90 Days
                        @break

                        @default
                            Over Time
                    @endswitch
                </h3>
                <div class="h-64" wire:ignore x-data="{
                    chart: null,
                    initChart() {
                        if (this.chart) {
                            this.chart.destroy();
                        }
                
                        // Make sure chartData is defined
                        if (!$wire.chartData || !$wire.chartData.labels) {
                            console.error('Bookings chart data is not available yet');
                            return;
                        }
                
                        // Create a clean copy of the data to avoid reference issues
                        const chartData = JSON.parse(JSON.stringify($wire.chartData));
                
                        // Wait for the DOM to be fully updated
                        setTimeout(() => {
                            const ctx = document.getElementById('bookingsChart')?.getContext('2d');
                            if (!ctx) {
                                console.error('Bookings canvas context not available');
                                return;
                            }
                
                            this.chart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: chartData.labels,
                                    datasets: [{
                                        label: 'Bookings',
                                        data: chartData.bookings || [],
                                        borderColor: 'rgb(59, 130, 246)',
                                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                        tension: 0.1,
                                        fill: true
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: false
                                        },
                                        tooltip: {
                                            mode: 'index',
                                            intersect: false
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                stepSize: 1
                                            }
                                        }
                                    }
                                }
                            });
                        }, 100);
                    }
                }" x-init="$nextTick(() => { setTimeout(() => initChart(), 500); })"
                    @chartDataUpdated.window="setTimeout(() => initChart(), 500)">
                    <canvas id="bookingsChart"></canvas>
                </div>
            </div>

            <!-- Revenue Chart -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    Revenue
                    @switch($selectedPeriod)
                        @case('7d')
                            Last 7 Days
                        @break

                        @case('30d')
                            Last 30 Days
                        @break

                        @case('90d')
                            Last 90 Days
                        @break

                        @default
                            Over Time
                    @endswitch
                </h3>
                <div class="h-64" wire:ignore x-data="{
                    chart: null,
                    initChart() {
                        if (this.chart) {
                            this.chart.destroy();
                        }
                
                        // Make sure chartData is defined
                        if (!$wire.chartData || !$wire.chartData.labels) {
                            console.error('Revenue chart data is not available yet');
                            return;
                        }
                
                        // Create a clean copy of the data to avoid reference issues
                        const chartData = JSON.parse(JSON.stringify($wire.chartData));
                
                        // Wait for the DOM to be fully updated
                        setTimeout(() => {
                            const ctx = document.getElementById('revenueChart')?.getContext('2d');
                            if (!ctx) {
                                console.error('Revenue canvas context not available');
                                return;
                            }
                
                            this.chart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: chartData.labels,
                                    datasets: [{
                                        label: 'Revenue',
                                        data: chartData.revenue || [],
                                        borderColor: 'rgb(34, 197, 94)',
                                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                        tension: 0.1,
                                        fill: true
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: false
                                        },
                                        tooltip: {
                                            mode: 'index',
                                            intersect: false,
                                            callbacks: {
                                                label: function(context) {
                                                    return 'Revenue: ' + context.parsed.y.toLocaleString('en-US', {
                                                        style: 'currency',
                                                        currency: '{{ $event->currency }}'
                                                    });
                                                }
                                            }
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                callback: function(value) {
                                                    return value.toLocaleString('en-US', {
                                                        style: 'currency',
                                                        currency: '{{ $event->currency }}'
                                                    });
                                                }
                                            }
                                        }
                                    }
                                }
                            });
                        }, 100);
                    }
                }" x-init="$nextTick(() => { setTimeout(() => initChart(), 500); })"
                    @chartDataUpdated.window="setTimeout(() => initChart(), 500)">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>
@push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush
