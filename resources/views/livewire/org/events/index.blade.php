<?php

use Livewire\Volt\Component;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

new class extends Component {
    public $search;
    public $eventTypeSelected;
    public $eventType;
    public $name;
    public $venue;
    public $venueData;
    public $event_repeat = 'Does not repeat';
    public $repeat_days = 1;
    public $start_date;
    public $end_date;
    public $start_time;
    public $end_time;
    public $timezone;
    public $currency;
    public $category;
    public $status = 'Draft';
    public $events = [];
    public int $organisationId;
    public $timezones = [];
    public $currencies = [];

    protected $rules = [
        'eventType' => 'required|string|max:50',
        'name' => 'required|string|max:255',
        'venue' => 'nullable|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'start_time' => 'required',
        'end_time' => 'required',
        'timezone' => 'required|string|max:100',
        'currency' => 'required|string|max:10',
        'category' => 'nullable|string|max:100',
        'status' => 'required|string|max:50',
    ];

    public function mount( int $organisationId = null)
    {
        $this->organisationId = $organisationId;
        $this->fetchEvents();
        $this->fetchTimezones();
        $this->fetchCurrencies();
    }

    public function fetchTimezones()
    {
        // Fetch timezones from API or use PHP's built-in timezone list
        $this->timezones = array_combine(timezone_identifiers_list(), timezone_identifiers_list());
    }

    public function fetchCurrencies()
    {
        // Fetch currencies from API or use a static list for now
        $this->currencies = [
            'USD' => 'US Dollar',
            'EUR' => 'Euro',
            'GBP' => 'British Pound',
            'JPY' => 'Japanese Yen',
            'AUD' => 'Australian Dollar',
            'CAD' => 'Canadian Dollar',
            'CHF' => 'Swiss Franc',
            'CNY' => 'Chinese Yuan',
            'INR' => 'Indian Rupee',
            'UGX' => 'Ugandan Shilling',
        ];
    }

    public function fetchEvents()
    {
        // If organization ID is provided, use it
        if ($this->organisationId) {
            $query = Event::where('organisation_id', $this->organisationId)->where('name', 'like', '%' . $this->search . '%');
        } else {
            // Fallback to all user's organizations
            $userOrganisations = Auth::user()->organisations()->pluck('id');
            $query = Event::whereIn('organisation_id', $userOrganisations)->where('name', 'like', '%' . $this->search . '%');
        }

        if (trim($this->eventTypeSelected) !== '') {
            $query->where('event_type', $this->eventTypeSelected);
        }

        $this->events = $query->get();
    }

    public function refresh()
    {
        $this->search = '';
        $this->eventTypeSelected = '';
        $this->fetchEvents();
    }

    public function store()
    {
        $this->validate();

        $start_datetime = Carbon::parse("{$this->start_date} {$this->start_time}");
        $end_datetime = Carbon::parse("{$this->start_date} {$this->end_time}");

        // Use the current organization ID or fallback to the first one
        $organisationId = $this->organisationId;

        if (!$organisationId) {
            $organisation = Auth::user()->organisations()->first();

            if (!$organisation) {
                $this->dispatch('toast', 'You need to create an organization first.', 'error', 'top-right');
                return;
            }

            $organisationId = $organisation->id;
        }

        // If event type is online, clear venue and venueData
        if ($this->eventType === 'online') {
            $this->venue = null;
            $this->venueData = null;
        }

        // Create event data array
        $eventData = [
            'organisation_id' => $organisationId,
            'event_type' => $this->eventType,
            'name' => $this->name,
            'venue' => $this->venue,
            'event_repeat' => $this->event_repeat,
            'start_date' => $this->start_date,
            'start_datetime' => $start_datetime,
            'end_datetime' => $end_datetime,
            'timezone' => $this->timezone,
            'currency' => $this->currency,
            'status' => $this->status,
            'category' => $this->category,
        ];

        // Add end_date if provided
        if ($this->end_date) {
            $eventData['end_date'] = $this->end_date;
        }

        // Add repeat_days to event data if event repeats
        if ($this->event_repeat !== 'Does not repeat') {
            $eventData['repeat_days'] = $this->repeat_days;
        }

        $event = Event::create($eventData);

        // Store location data if available
        if ($this->venueData) {
            $event->location()->create([
                'place_id' => $this->venueData['place_id'],
                'osm_id' => $this->venueData['osm_id'],
                'osm_type' => $this->venueData['osm_type'],
                'latitude' => $this->venueData['lat'],
                'longitude' => $this->venueData['lon'],
                'display_name' => $this->venueData['display_name'],
                'display_place' => $this->venueData['display_place'],
                'display_address' => $this->venueData['display_address'],
                'country' => $this->venueData['address']['country'] ?? null,
                'country_code' => $this->venueData['address']['country_code'] ?? null,
                'type' => $this->venueData['type'],
                'class' => $this->venueData['class'],
                'bounds' => $this->venueData['boundingbox'] ?? null,
            ]);
        }

        $this->resetInputFields();
        Flux::modals('create-event')->close();
        $this->dispatch('toast', 'Event created successfully.', 'success', 'top-right');
        $this->fetchEvents();
    }

    public function resetInputFields()
    {
        $this->reset(['eventType', 'name', 'venue', 'venueData', 'start_date', 'end_date', 'start_time', 'end_time', 'timezone', 'currency', 'category', 'repeat_days']);
        $this->event_repeat = 'Does not repeat';
        $this->status = 'Draft';
    }

    public function delete($id)
    {
        if ($id) {
            Event::findOrFail($id)->delete();
            Flux::modal('delete-event')->close();
            $this->dispatch('toast', 'Event draft deleted successfully', 'success', 'top-right');
            $this->eventToDelete = null;
            $this->fetchEvents();
        }
    }
}; ?>

<div>
    <!-- Search and filters header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto items-start sm:items-center">
            <!-- Search bar -->
            <div class="relative w-full sm:w-60">
                <input wire:model="search" wire:keyup="fetchEvents" type="text" placeholder="Search"
                    class="pl-10 pr-4 py-2 border rounded-lg w-full dark:bg-black dark:border-gray-700 dark:text-gray-200 dark:placeholder-gray-400">
                <svg wire:loading.remove wire:target="search" xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <svg class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" wire:loading
                    wire:target="search" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                    <circle fill="none" stroke-opacity="1" stroke="#FF156D" stroke-width=".5" cx="100"
                        cy="100" r="0">
                        <animate attributeName="r" calcMode="spline" dur="2" values="1;80" keyTimes="0;1"
                            keySplines="0 .2 .5 1" repeatCount="indefinite"></animate>
                        <animate attributeName="stroke-width" calcMode="spline" dur="2" values="0;25"
                            keyTimes="0;1" keySplines="0 .2 .5 1" repeatCount="indefinite"></animate>
                        <animate attributeName="stroke-opacity" calcMode="spline" dur="2" values="1;0"
                            keyTimes="0;1" keySplines="0 .2 .5 1" repeatCount="indefinite"></animate>
                    </circle>
                </svg>

            </div>

            <!-- Event filter dropdown -->
            <div class="w-full sm:w-auto relative">
                <select wire:model="eventTypeSelected" wire:change="fetchEvents" placeholder="Choose event..."
                    class="w-full border rounded p-2">
                    <option value="">All</option>
                    <option value="venue">Venue Event</option>
                    <option value="online">Online Event</option>
                    <option value="undecided">Undecided</option>
                </select>
            </div>
        </div>

        <!-- Action buttons -->
        <div class="flex gap-2 w-full sm:w-auto justify-end">
            <flux:modal.trigger name="create-event">
                <flux:button variant="primary" class="border-none outline-none cursor-pointer bg-teal-500">Create Event
                </flux:button>
            </flux:modal.trigger>

            <!-- Modal for creating event -->
            <flux:modal name="create-event" variant="flyout">
                <div class="space-y-8">
                    <form wire:submit.prevent="store">
                        <div>
                            <flux:heading size="lg">Create Event</flux:heading>
                            <flux:text class="mt-2">Fill in the details for your new event.</flux:text>
                        </div>

                        <!-- Event Type Selection -->
                        <div>
                            <flux:radio.group wire:model="eventType" label="Select the event type" variant="segmented"
                                required responsive>
                                <flux:radio label="Venue" value="venue" />
                                <flux:radio label="Online" value="online" />
                                <flux:radio label="Undecided" value="undecided" />
                            </flux:radio.group>
                            {{-- @error('eventType') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror --}}
                        </div>

                        <!-- Event Name -->
                        <div>
                            <flux:label required>Event name</flux:label>
                            <flux:input wire:model="name" placeholder="Enter event name" class="mt-2 w-full" />
                            @error('name')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Venue Selection - Only show if event type is venue or undecided -->
                        <div class="relative" x-data="venueSearch()" x-show="$wire.eventType !== 'online'">
                            <flux:label required>Select a venue</flux:label>
                            <div class="relative mt-2">
                                <!-- Search Input -->
                                <div class="relative">
                                    <input wire:model="venue" x-model="query"
                                        x-on:input.debounce.250ms="fetchSuggestions()" type="text"
                                        class="w-full px-4 py-2.5 pl-10 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent dark:bg-black dark:border-gray-700 dark:text-gray-200"
                                        placeholder="Search for a venue..." />
                                    <!-- Search Icon -->
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <!-- Loading indicator -->
                                    <div x-show="isLoading" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="animate-spin h-4 w-4 text-gray-500"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Suggestions Dropdown -->
                                <div x-show="suggestions.length > 0" x-transition
                                    class="absolute z-[9999] w-full mt-1 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                                    <template x-for="suggestion in suggestions" :key="suggestion.place_id">
                                        <div @click="selectVenue(suggestion)"
                                            class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-700 last:border-0">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-200"
                                                x-text="suggestion.display_place"></div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"
                                                x-text="suggestion.display_address"></div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            @error('venue')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror

                            <div class="mt-2">
                                <a href="#" class="text-accent text-sm hover:underline">Add Manually</a>
                            </div>
                        </div>

                        <!-- Date & Time -->
                        <div>
                            <flux:label required>Select event date & time</flux:label>

                            <div class="mt-4">
                                <flux:label>Event repeat</flux:label>
                                <select wire:model="event_repeat" class="w-full border rounded p-2 mt-1">
                                    <option value="Does not repeat">Does not repeat</option>
                                    <option value="Daily">Daily</option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Monthly">Monthly</option>
                                </select>
                            </div>

                            <!-- Number of days - Only show if event repeat is not 'Does not repeat' -->
                            <div class="mt-4" x-data="{}"
                                x-show="$wire.event_repeat !== 'Does not repeat'">
                                <flux:label>Number of days</flux:label>
                                <flux:input wire:model="repeat_days" type="number" min="1" class="mt-1"
                                    placeholder="Enter number of days" />
                            </div>

                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <flux:label>Start date</flux:label>
                                    <flux:input wire:model="start_date" type="date" class="mt-1" />
                                    @error('start_date')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div x-data="{}" x-show="$wire.event_repeat !== 'Does not repeat'">
                                    <flux:label>End date</flux:label>
                                    <flux:input wire:model="end_date" type="date" class="mt-1" />
                                    @error('end_date')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <flux:label>Start time</flux:label>
                                    <flux:input wire:model="start_time" type="time" class="mt-1" />
                                    @error('start_time')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <flux:label>End time</flux:label>
                                    <flux:input wire:model="end_time" type="time" class="mt-1" />
                                    <div class="text-sm text-gray-400 mt-1">Event ends on same day if no end date</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <flux:label>Timezone</flux:label>

                                    <select wire:model="timezone" class="w-full border rounded p-2 mt-2">
                                        <option value="">Select Timezone</option>
                                        @foreach ($timezones as $tz)
                                            <option value="{{ $tz }}">{{ $tz }}</option>
                                        @endforeach
                                    </select>
                                    @error('timezone')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <flux:label>Select currency</flux:label>
                                    <select wire:model="currency" class="w-full border rounded p-2 mt-2">
                                        <option value="">Select currency</option>
                                        @foreach ($currencies as $code => $name)
                                            <option value="{{ $code }}">{{ $code }} -
                                                {{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('currency')
                                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Event Category -->
                            <div class="mt-4">
                                <flux:label>Choose a category for your event.</flux:label>
                                <flux:text class="text-sm text-gray-600 mt-1">
                                    Please specify one or more categories that best represent your event.
                                </flux:text>
                                <select wire:model="category" class="w-full border rounded p-2 mt-2">
                                    <option value="">Select Category</option>
                                    <option value="Music">Music</option>
                                    <option value="Sports">Sports</option>
                                    <option value="Conferences">Conferences</option>
                                    <!-- More categories as needed -->
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <flux:spacer />
                            <flux:button type="submit" variant="primary">Save Draft Event</flux:button>
                        </div>
                    </form>
                </div>
            </flux:modal>

            <flux:button wire:click="refresh"
                class="bg-gray-100 dark:bg-gray-700 p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 dark:text-gray-200" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </flux:button>

        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 p-3 mb-4 rounded-md">
            {{ session('message') }}
        </div>
    @endif

    <!-- Events list -->
    <div class="relative">
        <!-- Loading Overlay -->
        <div wire:loading wire:target="eventTypeSelected, search, refresh"
            class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                <rect fill="#FF156D" stroke="#FF156D" stroke-width="15" stroke-linejoin="round" width="30"
                    height="30" x="85" y="85" rx="0" ry="0">
                    <animate attributeName="rx" calcMode="spline" dur="2" values="15;15;5;15;15"
                        keySplines=".5 0 .5 1;.8 0 1 .2;0 .8 .2 1;.5 0 .5 1" repeatCount="indefinite"></animate>
                    <animate attributeName="ry" calcMode="spline" dur="2" values="15;15;10;15;15"
                        keySplines=".5 0 .5 1;.8 0 1 .2;0 .8 .2 1;.5 0 .5 1" repeatCount="indefinite"></animate>
                    <animate attributeName="height" calcMode="spline" dur="2" values="30;30;1;30;30"
                        keySplines=".5 0 .5 1;.8 0 1 .2;0 .8 .2 1;.5 0 .5 1" repeatCount="indefinite"></animate>
                    <animate attributeName="y" calcMode="spline" dur="2" values="40;170;40;"
                        keySplines=".6 0 1 .4;0 .8 .2 1" repeatCount="indefinite"></animate>
                </rect>
            </svg>
        </div>
        @forelse ($events as $event)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition-shadow duration-300 cursor-pointer mb-4"
                wire:key="{{ $event->id }}">
                <!-- Event card -->
                <div class="border-b dark:border-gray-700 p-4">
                    <div class="flex items-start">
                        <!-- Event image -->
                        <div class="w-24 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg mr-4 overflow-hidden relative">
                            @if ($event->banners && count(json_decode($event->banners, true)) > 0)
                                <!-- Low quality image preview -->
                                <div class="absolute inset-0 bg-cover bg-center filter blur-xl scale-110 transform opacity-50 transition-opacity duration-500"
                                    style="background-image: url('{{ json_decode($event->banners, true)[0] }}?quality=1&w=20');">
                                </div>
                                <!-- Skeleton loader -->
                                <div class="absolute inset-0 skeleton-loader shimmer flex items-center justify-center transition-opacity duration-300">
                                    <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <!-- Main image -->
                                <img src="{{ json_decode($event->banners, true)[0] }}"
                                    alt="{{ $event->name }}"
                                    class="w-full h-full object-cover relative z-10 transition-opacity duration-500"
                                    loading="lazy"
                                    onload="this.previousElementSibling.style.opacity = 0; this.previousElementSibling.previousElementSibling.style.opacity = 0;"
                                    onerror="this.style.display='none'; this.previousElementSibling.innerHTML='<svg class=\'w-6 h-6 text-gray-400 dark:text-gray-500\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z\' /></svg>';">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 dark:text-gray-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Event details -->
                        <div class="flex-1">
                            <div class="flex justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $event->name }}
                                    </h3>
                                    @if ($event->isArchived())
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200 mt-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                            </svg>
                                            Archived
                                        </span>
                                    @endif
                                </div>
                                <!-- Options button -->
                                <flux:dropdown>
                                    <flux:button variant="ghost" icon="ellipsis-vertical" class="p-1">
                                    </flux:button>

                                    <flux:menu>
                                        <flux:menu.item :href="route('event-details', ['id' => $event->id])"
                                            wire:navigate>Manage</flux:menu.item>
                                        <flux:menu.item>preview</flux:menu.item>

                                        <flux:menu.separator />

                                        <flux:menu.item>duplicate</flux:menu.item>
                                        <flux:menu.item>Cancel</flux:menu.item>

                                        <flux:menu.separator />


                                        <flux:modal.trigger :name="'delete-event-'.$event->id">
                                            <flux:menu.item variant="danger" :name="'delete-event-'.$event->id">
                                                delete
                                            </flux:menu.item>
                                        </flux:modal.trigger>
                                        @teleport('body')
                                            <!-- Delete Event Modal -->
                                            <flux:modal :name="'delete-event-'.$event->id" class="min-w-[22rem]">
                                                <div class="space-y-6">
                                                    <div>
                                                        <flux:heading size="lg">Delete Event?</flux:heading>

                                                        <flux:text class="mt-2">
                                                            <p>You're about to delete this event.</p>
                                                            <p>This action cannot be reversed.</p>
                                                        </flux:text>
                                                    </div>

                                                    <div class="flex gap-2">
                                                        <flux:spacer />

                                                        <flux:modal.close>
                                                            <flux:button variant="ghost">Cancel</flux:button>
                                                        </flux:modal.close>

                                                        <flux:button variant="danger"
                                                            wire:click="delete({{ $event->id }})"
                                                            class="cursor-pointer">Delete Event</flux:button>
                                                    </div>
                                                </div>
                                            </flux:modal>
                                        @endteleport

                                    </flux:menu>
                                </flux:dropdown>
                            </div>

                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400 mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $event->venue }}
                            </div>
                        </div>
                    </div>

                    <flux:separator class="mt-5" />

                    <!-- Event stats -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                        <!-- Status -->
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 text-gray-500 dark:text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Status</div>
                                <div class="flex items-center text-sm">
                                    <span class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></span>
                                    <span class="text-gray-900 dark:text-gray-200">{{ $event->status }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 text-gray-500 dark:text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Starts on</div>
                                <div class="text-gray-900 dark:text-gray-200 text-sm">{{ $event->start_datetime }}
                                    ({{ $event->timezone }})
                                </div>
                            </div>
                        </div>

                        <!-- Ticket -->
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 text-gray-500 dark:text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Tickets</div>
                                <div class="text-gray-900 dark:text-gray-200 text-sm">
                                    {{ $event->tickets->where('status', 'active')->count() }} types
                                </div>
                            </div>
                        </div>

                        <!-- Sold -->
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 text-gray-500 dark:text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">QTY Sold</div>
                                <div class="text-gray-900 dark:text-gray-200 text-sm">
                                    {{ $event->tickets->sum('quantity_sold') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-black rounded-lg shadow">
                <div class="p-4">
                    <flux:text>No events found.</flux:text>
                </div>
            </div>
        @endforelse
    </div>
</div>

@push('scripts')
    <script>
        function venueSearch() {
            return {
                query: '',
                suggestions: [],
                isLoading: false,

                // edevaour to find ur own api keys
                locationiqKey: 'pk.8da423155473007977a90bb555d54b41',

                async fetchSuggestions() {
                    if (this.query.length < 3) {
                        this.suggestions = [];
                        return;
                    }

                    this.isLoading = true;
                    try {
                        const response = await fetch(
                            `https://api.locationiq.com/v1/autocomplete?key=${this.locationiqKey}&q=${encodeURIComponent(this.query)}&format=json&limit=5`
                        );
                        const data = await response.json();

                        if (Array.isArray(data)) {
                            this.suggestions = data.map(item => ({
                                place_id: item.place_id,
                                display_place: item.display_place,
                                display_address: item.display_address,
                                display_name: item.display_name,
                                lat: item.lat,
                                lon: item.lon,
                                osm_id: item.osm_id,
                                osm_type: item.osm_type,
                                type: item.type,
                                class: item.class,
                                address: item.address,
                                boundingbox: item.boundingbox
                            }));
                        } else {
                            this.suggestions = [];
                        }
                    } catch (error) {
                        console.error('Error fetching suggestions:', error);
                        this.suggestions = [];
                    } finally {
                        this.isLoading = false;
                    }
                },

                selectVenue(suggestion) {
                    this.query = suggestion.display_name;
                    @this.set('venue', suggestion.display_name);
                    @this.set('venueData', suggestion);
                    this.suggestions = [];
                }
            }
        }
    </script>
@endpush
