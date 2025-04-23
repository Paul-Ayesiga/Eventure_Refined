<?php

namespace App\Livewire\User;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class Events extends Component
{
    use WithPagination;

    // Filters
    public $searchQuery = '';
    public $selectedCategory = 'All';
    public $selectedDate = 'All';
    public $selectedLocation = '';

    // Date filters
    public $dateFilters = [
        'All' => 'All',
        'Today' => 'Today',
        'Tomorrow' => 'Tomorrow',
        'This Week' => 'This Week',
        'This Weekend' => 'This Weekend',
        'Next Week' => 'Next Week',
        'Next Weekend' => 'Next Weekend',
        'This Month' => 'This Month',
        'Next Month' => 'Next Month',
        'This Year' => 'This Year',
        'Next Year' => 'Next Year'
    ];

    // Category filters
    public $categories = [
        'All' => 'All',
        'Arts' => 'Arts',
        'Business' => 'Business',
        'Music and Theater' => 'Music and Theater',
        'Community and Culture' => 'Community and Culture',
        'Sports and Fitness' => 'Sports and Fitness',
        'Education and Training' => 'Education and Training'
    ];

    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'selectedCategory' => ['except' => 'All'],
        'selectedDate' => ['except' => 'All'],
        'selectedLocation' => ['except' => '']
    ];

    public function updatedSearchQuery()
    {
        $this->resetPage();
    }

    public function updatedSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatedSelectedDate()
    {
        $this->resetPage();
    }

    public function updatedSelectedLocation()
    {
        $this->resetPage();
    }

    public function getDateRange($filter)
    {
        $today = Carbon::today();

        switch ($filter) {
            case 'Today':
                return [$today, $today];
            case 'Tomorrow':
                $tomorrow = $today->copy()->addDay();
                return [$tomorrow, $tomorrow];
            case 'This Week':
                return [$today->startOfWeek(), $today->copy()->endOfWeek()];
            case 'This Weekend':
                $saturday = $today->copy()->startOfWeek()->addDays(5); // Saturday
                $sunday = $today->copy()->startOfWeek()->addDays(6); // Sunday
                return [$saturday, $sunday];
            case 'Next Week':
                $nextWeekStart = $today->copy()->addWeek()->startOfWeek();
                $nextWeekEnd = $today->copy()->addWeek()->endOfWeek();
                return [$nextWeekStart, $nextWeekEnd];
            case 'Next Weekend':
                $nextSaturday = $today->copy()->addWeek()->startOfWeek()->addDays(5); // Next Saturday
                $nextSunday = $today->copy()->addWeek()->startOfWeek()->addDays(6); // Next Sunday
                return [$nextSaturday, $nextSunday];
            case 'This Month':
                return [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()];
            case 'Next Month':
                $nextMonthStart = $today->copy()->addMonth()->startOfMonth();
                $nextMonthEnd = $today->copy()->addMonth()->endOfMonth();
                return [$nextMonthStart, $nextMonthEnd];
            case 'This Year':
                return [$today->copy()->startOfYear(), $today->copy()->endOfYear()];
            case 'Next Year':
                $nextYearStart = $today->copy()->addYear()->startOfYear();
                $nextYearEnd = $today->copy()->addYear()->endOfYear();
                return [$nextYearStart, $nextYearEnd];
            default:
                return [null, null]; // All dates
        }
    }

    public function render()
    {
        $query = Event::with(['location', 'organisation'])
            ->where('status', 'Published');

        // Apply search filter
        if (!empty($this->searchQuery)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('description', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('venue', 'like', '%' . $this->searchQuery . '%');
            });
        }

        // Apply category filter
        if ($this->selectedCategory !== 'All') {
            $query->where('category', $this->selectedCategory);
        }

        // Apply date filter
        if ($this->selectedDate !== 'All') {
            [$startDate, $endDate] = $this->getDateRange($this->selectedDate);

            if ($startDate && $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                      ->orWhereBetween('end_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                      ->orWhere(function ($subQ) use ($startDate, $endDate) {
                          $subQ->where('start_date', '<=', $startDate->format('Y-m-d'))
                               ->where('end_date', '>=', $endDate->format('Y-m-d'));
                      });
                });
            }
        }

        // Apply location filter
        if (!empty($this->selectedLocation)) {
            $query->whereHas('location', function ($q) {
                $q->where('display_name', 'like', '%' . $this->selectedLocation . '%')
                  ->orWhere('display_place', 'like', '%' . $this->selectedLocation . '%')
                  ->orWhere('display_address', 'like', '%' . $this->selectedLocation . '%')
                  ->orWhere('country', 'like', '%' . $this->selectedLocation . '%');
            });
        }

        $events = $query->orderBy('start_date', 'asc')->paginate(12);

        return view('livewire.user.events', [
            'events' => $events
        ]);
    }
}
