<?php

namespace App\Livewire\Org;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Organisation;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

#[Layout('components.layouts.organisation')]
class Dashboard extends Component
{
    public $dateRange = 'weekly';
    public $sortBy = 'bookings';
    public $selectedEvents = [];
    public $startDate;
    public $endDate;
    public $organisationId;
    public $orgEvents;
    public $isChartDataLoaded = false;
    public $chartData = [];
    public $statistics = [];
    public $organisation;
    public $currency;
    public function mount($organisationId)
    {
        $this->organisationId = $organisationId;
        // Set default date range to current month
        $this->startDate = Carbon::now()->startOfMonth();
        $this->endDate = Carbon::now();
        $this->orgEvents = Event::where('organisation_id', $this->organisationId)->pluck('id')->toArray();

        // Get the organisation and its currency
        $this->organisation = Organisation::find($this->organisationId);
        $this->currency = $this->organisation->currency ?? 'USD';
    }

    #[On('booking-changed')]
    public function refreshData()
    {
        // Refresh the events list in case new events were added
        $this->orgEvents = Event::where('organisation_id', $this->organisationId)->pluck('id')->toArray();

        // Reload the chart data
        $this->loadChartData();

        // Dispatch event to update the chart
        $this->dispatch('chartDataUpdated');
    }

    public function updated($property)
    {
        // Only refresh chart data when specific properties change
        if (in_array($property, ['dateRange', 'sortBy', 'startDate', 'endDate'])) {
            $this->loadChartData();
            $this->dispatch('chartDataUpdated');
        }

        // Special handling for selectedEvents array
        if (str_starts_with($property, 'selectedEvents')) {
            $this->loadChartData();
            $this->dispatch('chartDataUpdated');
        }
    }

    public function setDateRange($range)
    {
        $this->dateRange = $range;
        $this->resetDates();
        $this->loadChartData();
        $this->dispatch('chartDataUpdated');
    }

    protected function resetDates()
    {
        $this->startDate = match($this->dateRange) {
            'daily' => Carbon::now()->startOfDay(),
            'weekly' => Carbon::now()->startOfWeek(),
            'monthly' => Carbon::now()->startOfMonth(),
        };
        $this->endDate = Carbon::now();
    }

    public function previousPeriod()
    {
        $diff = $this->startDate->diffInDays($this->endDate);
        $this->startDate->subDays($diff);
        $this->endDate->subDays($diff);
        $this->loadChartData();
        $this->dispatch('chartDataUpdated');
    }

    public function nextPeriod()
    {
        $diff = $this->startDate->diffInDays($this->endDate);
        $this->startDate->addDays($diff);
        $this->endDate->addDays($diff);
        $this->loadChartData();
        $this->dispatch('chartDataUpdated');
    }

    public function toggleEvent($eventId)
    {
        if (in_array($eventId, $this->selectedEvents)) {
            $this->selectedEvents = array_diff($this->selectedEvents, [$eventId]);
        } else {
            $this->selectedEvents[] = $eventId;
        }

        // Refresh the statistics and chart data when events selection changes
        $this->loadChartData();
        $this->isChartDataLoaded = true;

        // Dispatch event to update the chart
        $this->dispatch('chartDataUpdated');
    }

    protected function getStatistics()
    {

        $query = Booking::query()
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('status', 'confirmed')
            ->whereIn('event_id', $this->orgEvents);

        if (!empty($this->selectedEvents)) {
            $query->whereIn('event_id', $this->selectedEvents);
        }

        $currentStats = $query->selectRaw('
            COUNT(*) as total_bookings,
            SUM(total_amount) as total_revenue,
            COUNT(DISTINCT user_id) as unique_customers
        ')->first();

        // Get previous period stats for comparison
        $previousStartDate = clone $this->startDate;
        $previousEndDate = clone $this->endDate;
        $previousStartDate->subDays($this->startDate->diffInDays($this->endDate));
        $previousEndDate = clone $this->startDate;

        $previousStats = Booking::query()
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->where('status', 'confirmed')
            ->whereIn('event_id', $this->orgEvents)
            ->when(!empty($this->selectedEvents), function($query) {
                $query->whereIn('event_id', $this->selectedEvents);
            })
            ->selectRaw('
                COUNT(*) as total_bookings,
                SUM(total_amount) as total_revenue,
                COUNT(DISTINCT user_id) as unique_customers
            ')->first();

        // Calculate percentage changes
        $revenueChange = $this->calculatePercentageChange(
            $previousStats->total_revenue ?? 0,
            $currentStats->total_revenue ?? 0
        );

        $ordersChange = $this->calculatePercentageChange(
            $previousStats->total_bookings ?? 0,
            $currentStats->total_bookings ?? 0
        );

        // Get tickets sold for this organization's events - current period
        $ticketsSold = DB::table('booking_items')
            ->join('bookings', 'bookings.id', '=', 'booking_items.booking_id')
            ->join('events', 'events.id', '=', 'bookings.event_id')
            ->where('events.organisation_id', $this->organisationId)
            ->whereBetween('bookings.created_at', [$this->startDate, $this->endDate])
            ->where('bookings.status', 'confirmed')
            ->when(!empty($this->selectedEvents), function($query) {
                $query->whereIn('bookings.event_id', $this->selectedEvents);
            })
            ->sum('booking_items.quantity');

        // Get tickets sold for previous period
        $previousTicketsSold = DB::table('booking_items')
            ->join('bookings', 'bookings.id', '=', 'booking_items.booking_id')
            ->join('events', 'events.id', '=', 'bookings.event_id')
            ->where('events.organisation_id', $this->organisationId)
            ->whereBetween('bookings.created_at', [$previousStartDate, $previousEndDate])
            ->where('bookings.status', 'confirmed')
            ->when(!empty($this->selectedEvents), function($query) {
                $query->whereIn('bookings.event_id', $this->selectedEvents);
            })
            ->sum('booking_items.quantity');

        // Calculate percentage change for tickets sold
        $ticketsSoldChange = $this->calculatePercentageChange(
            $previousTicketsSold,
            $ticketsSold
        );

        return [
            'revenue' => [
                'current' => $currentStats->total_revenue ?? 0,
                'change' => $revenueChange
            ],
            'bookings' => [
                'current' => $currentStats->total_bookings ?? 0,
                'change' => $ordersChange
            ],
            'tickets_sold' => [
                'current' => $ticketsSold,
                'change' => $ticketsSoldChange
            ],
            'page_views' => [
                'current' => 0, // Add page view tracking logic
                'change' => 0
            ]
        ];
    }

    protected function calculatePercentageChange($previous, $current)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    public function getAvailableEvents()
    {
        return Event::with('organisation')
            ->where('organisation_id', $this->organisationId)
            ->get();
    }

    protected function getChartData()
    {
        $data = [];
        $labels = [];
        $current = clone $this->startDate;

        while ($current <= $this->endDate) {
            $labels[] = $current->format('M j');

            $dailyStats = Booking::query()
                ->whereDate('created_at', $current)
                ->where('status', 'confirmed')
                ->whereIn('event_id', $this->orgEvents)
                ->when(!empty($this->selectedEvents), function($query) {
                    $query->whereIn('event_id', $this->selectedEvents);
                })
                ->selectRaw('
                    COUNT(*) as bookings,
                    SUM(total_amount) as revenue
                ')->first();

            $data['bookings'][] = $dailyStats->bookings ?? 0;
            $data['revenue'][] = $dailyStats->revenue ?? 0;

            $current->addDay();
        }

        return [
            'labels' => $labels,
            'datasets' => $data
        ];
    }

    public function loadChartData()
    {
        $this->statistics = $this->getStatistics();
        $this->chartData = $this->getChartData();
        $this->isChartDataLoaded = true;
    }

    public function render()
    {
        $availableEvents = $this->getAvailableEvents();

        // If data isn't loaded yet, load it now
        if (!$this->isChartDataLoaded) {
            $this->loadChartData();
        }

        return view('livewire.org.dashboard', [
            'statistics' => $this->statistics,
            'availableEvents' => $availableEvents,
            'chartData' => $this->chartData
        ]);
    }
}
