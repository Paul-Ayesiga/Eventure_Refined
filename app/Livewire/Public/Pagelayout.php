<?php

namespace App\Livewire\Public;

use App\Models\Event;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Pagelayout extends Component
{
    public $featuredEvents = [];
    public $upcomingEvents = [];
    public $categories = [];
    public $searchQuery = '';

    public function mount()
    {
        $this->loadFeaturedEvents();
        $this->loadUpcomingEvents();
        $this->loadCategories();
    }

    public function loadFeaturedEvents()
    {
        // Get featured events (published events with the most tickets sold)
        $this->featuredEvents = Event::where('status', 'Published')
            ->where('is_archived', false)
            ->where('start_date', '>=', now()->format('Y-m-d'))
            ->withCount(['tickets as tickets_sold_sum' => function ($query) {
                $query->select(DB::raw('SUM(quantity_sold)'));
            }])
            ->orderBy('tickets_sold_sum', 'desc')
            ->take(6)
            ->get();
    }

    public function loadUpcomingEvents()
    {
        // Get upcoming events (published events with the closest start date)
        $this->upcomingEvents = Event::where('status', 'Published')
            ->where('is_archived', false)
            ->where('start_date', '>=', now()->format('Y-m-d'))
            ->orderBy('start_date', 'asc')
            ->take(8)
            ->get();
    }

    public function loadCategories()
    {
        // Get all unique categories that have at least one published event
        $this->categories = Event::where('status', 'Published')
            ->where('is_archived', false)
            ->where('start_date', '>=', now()->format('Y-m-d'))
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->take(10)
            ->toArray();
    }

    public function search()
    {
        if (empty($this->searchQuery)) {
            return;
        }

        return redirect()->route('user.events', ['searchQuery' => $this->searchQuery]);
    }

    public function render()
    {
        return view('livewire.public.Pagelayout')->layout('components.layouts.page');
    }


}
