<?php

namespace App\Livewire\Org\Events;

use App\Models\Event;
use App\Models\Booking;
use App\Models\Ticket;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class Insights extends Component
{
    use WithPagination;

    public $eventId;
    public $event;
    public $selectedPeriod = '30d'; // 7d, 30d, 90d, all
    public $chartData = [];
    public $stats = [];
    public $isChartDataLoaded = false;


    public function mount($id)
    {
        $this->eventId = $id;
        $this->event = Event::with(['bookings', 'tickets'])->findOrFail($id);
        $this->loadStats();
        $this->loadChartData();

        // Check if user is authorized to manage this event
        // if ($this->event->organiser->organiser_id !== Auth::id()) {
        //     abort(403, 'Unauthorized action.');
        // }
    }

    #[On('booking-changed')]
    public function refreshData()
    {
        $this->event = Event::with(['bookings', 'tickets'])->findOrFail($this->eventId);
        $this->loadStats();
        $this->loadChartData();
    }

    public function loadStats()
    {
        $bookings = $this->getFilteredBookings();
        $tickets = $this->event->tickets;

        $this->stats = [
            'total_bookings' => $bookings->count(),
            'total_revenue' => $bookings->sum('total_amount'),
            'total_tickets_sold' => $tickets->sum('quantity_sold'),
            'average_ticket_price' => $tickets->avg('price'),
            'conversion_rate' => $this->calculateConversionRate($bookings),
            'top_selling_ticket' => $this->getTopSellingTicket($tickets),
        ];
    }

    public function loadChartData()
    {
        $bookings = $this->getFilteredBookings();

        // Group bookings by date and ensure dates are in order
        $groupedBookings = $bookings->sortBy('created_at')->groupBy(function ($booking) {
            return $booking->created_at->format('Y-m-d');
        });

        // Ensure we have at least one data point
        if ($groupedBookings->isEmpty()) {
            $this->chartData = [
                'labels' => [now()->format('Y-m-d')],
                'bookings' => [0],
                'revenue' => [0],
            ];
        } else {
            $this->chartData = [
                'labels' => $groupedBookings->keys()->toArray(),
                'bookings' => $groupedBookings->map->count()->values()->toArray(),
                'revenue' => $groupedBookings->map(function ($bookings) {
                    return $bookings->sum('total_amount');
                })->values()->toArray(),
            ];
        }

        // Mark data as loaded
        $this->isChartDataLoaded = true;

        // Dispatch event to notify charts to update
        $this->dispatch('chartDataUpdated');
    }

    public function updatedSelectedPeriod()
    {
        $this->loadStats();
        $this->loadChartData();
    }

    private function getFilteredBookings()
    {
        $query = $this->event->bookings();

        switch ($this->selectedPeriod) {
            case '7d':
                $query->where('created_at', '>=', now()->subDays(7));
                break;
            case '30d':
                $query->where('created_at', '>=', now()->subDays(30));
                break;
            case '90d':
                $query->where('created_at', '>=', now()->subDays(90));
                break;
                // 'all' is the default, no filter needed
        }

        return $query->get();
    }

    private function calculateConversionRate($bookings)
    {
        $totalVisitors = $this->event->views ?? 1; // Fallback to 1 to avoid division by zero
        return round(($bookings->count() / $totalVisitors) * 100, 2);
    }

    private function getTopSellingTicket($tickets)
    {
        return $tickets->sortByDesc('quantity_sold')->first();
    }

    public function render()
    {
        // If data isn't loaded yet, ensure it's loaded
        if (!$this->isChartDataLoaded) {
            $this->loadStats();
            $this->loadChartData();
        }

        return view('livewire.org.events.insights')->layout('components.layouts.event-detail', [
            'eventId' => $this->eventId,
            'event' => $this->event
        ]);
    }
}
