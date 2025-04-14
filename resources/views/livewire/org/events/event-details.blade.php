<div class="max-w-7xl mx-auto">
    <!-- Hero Section -->
    <div
        class="left-0 right-0 bg-gradient-to-r from-lime-200 to-blue-700 dark:from-teal-800 dark:to-teal-900 rounded-2xl">
        <div class="px-4 py-8 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                    Event Management
                </h1>
                <p class="mt-3 text-lg text-teal-100">
                    Create and manage your event details, including scheduling, venue information, and event settings.
                </p>
            </div>
        </div>
    </div>

    <div class="p-4 sm:p-6 lg:p-8">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold dark:text-white">{{ $event->name }}</h1>

            </div>
            <div class="flex gap-2">
                @if (!$isEditing)
                    <flux:button wire:click="toggleEdit" variant="primary" class="cursor-pointer">Edit Event
                    </flux:button>
                @endif
            </div>
        </div>

        @if ($isEditing)
            <form wire:submit.prevent="update" class="space-y-6">
                <!-- Basic Information Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4 dark:text-white">Basic Information</h2>

                    <!-- Event Type -->
                    <div class="mb-6">
                        <flux:radio.group wire:model="eventType" label="Event type" variant="segmented" required>
                            <flux:radio label="Venue Event" value="venue" />
                            <flux:radio label="Online Event" value="online" />
                            <flux:radio label="Undecided" value="undecided" />
                        </flux:radio.group>
                    </div>

                    <!-- Event Name -->
                    <div class="mb-6">
                        <flux:label required>Event name</flux:label>
                        <flux:input wire:model="name" class="mt-2 w-full" />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Venue -->
                    <div class="mb-6">
                        <flux:label>Venue</flux:label>
                        <div class="relative" x-data="venueSearch()">
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
                        </div>
                        @error('venue')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Map -->
                    @if ($event->location)
                        <div class="mt-4">
                            <div id="edit-map" class="h-64 w-full rounded-lg relative">
                                <!-- Professional Map Placeholder -->
                                <div id="edit-map-placeholder"
                                    class="absolute inset-0 bg-gray-100 dark:bg-gray-800 rounded-lg overflow-hidden">
                                    <!-- Skeleton loader with shimmer -->
                                    <div class="h-full w-full skeleton-loader shimmer">
                                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                                            <!-- Map icon with pulse effect -->
                                            <div class="rounded-full bg-gray-200 dark:bg-gray-700 p-4 mb-3 pulse">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-8 w-8 text-gray-400 dark:text-gray-500" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </div>
                                            <!-- Loading text -->
                                            <div class="space-y-2 text-center">
                                                <p class="text-gray-600 dark:text-gray-300 text-sm font-medium">Loading
                                                    map...</p>
                                                <p class="text-gray-400 dark:text-gray-500 text-xs">{{ $event->venue }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Category -->
                    <div class="mb-6">
                        <flux:label>Category</flux:label>
                        <select wire:model="category"
                            class="w-full border rounded p-2 mt-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Select Category</option>
                            <option value="Music">Music</option>
                            <option value="Sports">Sports</option>
                            <option value="Conferences">Conferences</option>
                            <option value="Workshops">Workshops</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>


                </div>

                <!-- Date and Time Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4 dark:text-white">Date and Time</h2>

                    <!-- Event Repeat -->
                    <div class="mb-6">
                        <flux:label>Event Repeat</flux:label>
                        <select wire:model="event_repeat"
                            class="w-full border rounded p-2 mt-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="Does not repeat">Does not repeat</option>
                            <option value="Daily">Daily</option>
                            <option value="Weekly">Weekly</option>
                            <option value="Monthly">Monthly</option>
                        </select>
                    </div>

                    <!-- Number of days - Only show if event repeat is not 'Does not repeat' -->
                    <div class="mb-6" x-data="{}" x-show="$wire.event_repeat !== 'Does not repeat'">
                        <flux:label>Number of days</flux:label>
                        <flux:input wire:model="repeat_days" type="number" min="1" class="mt-1 w-full"
                            placeholder="Enter number of days" />
                        @error('repeat_days')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End date - Only show if event repeat is not 'Does not repeat' -->
                    <div class="mb-6" x-data="{}" x-show="$wire.event_repeat !== 'Does not repeat'">
                        <flux:label>End date</flux:label>
                        <flux:input wire:model="end_date" type="date" class="mt-1 w-full" />
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">The last day of the event</p>
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div>
                            <flux:label required>Start date</flux:label>
                            <flux:input wire:model="start_date" type="date" class="mt-1 w-full" />
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <flux:label required>Start time</flux:label>
                            <flux:input wire:model="start_time" type="time" class="mt-1 w-full" />
                            @error('start_time')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div>
                            <flux:label required>End time</flux:label>
                            <flux:input wire:model="end_time" type="time" class="mt-1 w-full" />
                        </div>
                        <div>
                            <flux:label required>Timezone</flux:label>
                            <select wire:model="timezone"
                                class="w-full border rounded p-2 mt-1 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Select Timezone</option>
                                <option value="UTC">UTC</option>
                                <option value="America/New_York">Eastern Time</option>
                                <option value="America/Chicago">Central Time</option>
                                <option value="America/Denver">Mountain Time</option>
                                <option value="America/Los_Angeles">Pacific Time</option>
                                <option value="Africa/Kampala">Africa/Kampala (GMT+03:00)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="convert_timezone"
                                class="form-checkbox h-5 w-5 text-teal-500">
                            <span class="ml-2 text-gray-700 dark:text-gray-200">Convert to attendee's timezone</span>
                        </label>

                    </div>
                </div>

                <!-- Settings Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4 dark:text-white">Event Settings</h2>

                    <div class="mb-6">
                        <flux:label required>Currency</flux:label>
                        <select wire:model="currency"
                            class="w-full border rounded p-2 mt-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Select Currency</option>
                            <option value="USD">USD - US Dollar</option>
                            <option value="EUR">EUR - Euro</option>
                            <option value="GBP">GBP - British Pound</option>
                            <option value="AUD">AUD - Australian Dollar</option>
                        </select>
                        @error('currency')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <div class="flex items-center justify-between">
                            <flux:label>Event visibility</flux:label>
                            <flux:switch wire:model.live="event_visibility" />
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            We may display your event on our explore events page if it meets our publishing guidelines.
                            It helps increase your event's discoverability, resulting in more bookings for your event.
                        </p>
                    </div>

                </div>

                <!-- Tickets Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-6 dark:text-white">Tickets</h2>
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-sm uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                                Active Tickets</h3>
                            <p class="mt-2 text-base font-medium dark:text-white">
                                {{ $event->tickets->where('status', 'active')->count() }} types
                            </p>
                            <flux:separator class="mt-4" />
                        </div>
                        <div>
                            <h3 class="text-sm uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                                Total Sold</h3>
                            <p class="mt-2 text-base font-medium dark:text-white">
                                {{ $event->tickets->sum('quantity_sold') }} tickets
                            </p>
                            <flux:separator class="mt-4" />
                        </div>
                        <div>
                            <h3 class="text-sm uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                                Revenue</h3>
                            <p class="mt-2 text-base font-medium dark:text-white">
                                {{ number_format($event->tickets->sum(function ($ticket) {return $ticket->quantity_sold * $ticket->price;}),2) }}
                                {{ $event->currency }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Event Details Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4 dark:text-white">Event Details</h2>

                    <!-- Description -->
                    <div class="mb-6">
                        <flux:label>Description</flux:label>
                        <flux:textarea wire:model="description" class="mt-2 w-full" rows="4" />
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tags -->
                    <div class="mb-6">
                        <flux:label>Tags</flux:label>
                        <div class="mt-2">
                            <!-- Display existing tags -->
                            <div class="flex flex-wrap gap-2 mb-2">
                                @foreach ($tags as $index => $tag)
                                    <span
                                        class="inline-flex items-center bg-gray-100 dark:bg-gray-700 rounded-full px-3 py-1">
                                        <span class="text-sm">{{ $tag }}</span>
                                        <button type="button" wire:click="removeTag({{ $index }})"
                                            class="ml-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </span>
                                @endforeach
                            </div>

                            <!-- Add new tag with dropdown -->
                            <div class="flex gap-2">
                                <select wire:model="newTag"
                                    class="flex-1 w-full border rounded-md p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">Select a tag</option>
                                    @foreach ($this->getAllowedTags() as $tag)
                                        <option value="{{ $tag }}">{{ $tag }}</option>
                                    @endforeach
                                </select>
                                <flux:button type="button" wire:click="addTag" variant="subtle">
                                    Add
                                </flux:button>
                            </div>

                            @error('newTag')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Banners -->
                    <div class="mb-6">
                        <flux:label>Event Banners</flux:label>
                        <div class="mt-2 space-y-4">
                            <!-- Existing banners with loader -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach ($banners as $index => $banner)
                                    <div class="relative group">
                                        <!-- Banner skeleton loader with preview -->
                                        <div class="relative w-full h-40 rounded-lg overflow-hidden">
                                            <!-- Skeleton loader -->
                                            <div
                                                class="absolute inset-0 skeleton-loader shimmer flex items-center justify-center">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-600 mb-2"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Loading
                                                        banner...</span>
                                                </div>
                                            </div>
                                            <!-- Image -->
                                            <img src="{{ $banner }}" alt="Event banner"
                                                class="w-full h-40 object-cover relative z-10"
                                                onload="this.style.opacity='1'; this.previousElementSibling.style.display='none';"
                                                onerror="this.style.display='none'; this.previousElementSibling.innerHTML='<div class=\'flex flex-col items-center\'><svg class=\'w-8 h-8 text-gray-400 dark:text-gray-600 mb-2\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z\' /></svg><span class=\'text-sm text-gray-500 dark:text-gray-400\'>Failed to load image</span></div>';"
                                                style="opacity: 0; transition: opacity 0.3s ease;" />
                                        </div>
                                        <!-- Hover overlay for delete -->
                                        <div
                                            class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center z-20">
                                            <flux:button wire:click="removeBanner({{ $index }})"
                                                variant="danger" class="bg-opacity-90">
                                                Remove
                                            </flux:button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Upload new banners -->
                            <div
                                class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 transition-all duration-300 hover:border-teal-500 dark:hover:border-teal-400">
                                <input type="file" wire:model="tempBanners" class="hidden" id="banner-upload"
                                    multiple accept="image/*">
                                <label for="banner-upload"
                                    class="cursor-pointer flex flex-col items-center justify-center text-gray-600 dark:text-gray-400">
                                    <div
                                        class="rounded-full bg-gray-100 dark:bg-gray-700 p-4 mb-3 transition-colors duration-300 group-hover:bg-teal-50 dark:group-hover:bg-teal-900">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium">Click to upload banners</span>
                                    <span class="text-xs mt-1">(Max 5MB per image)</span>
                                </label>

                                <!-- Upload progress indicator -->
                                <div wire:loading wire:target="tempBanners" class="mt-4">
                                    <div class="flex items-center justify-center space-x-2">
                                        <div class="w-2 h-2 rounded-full bg-teal-500 animate-bounce"></div>
                                        <div class="w-2 h-2 rounded-full bg-teal-500 animate-bounce"
                                            style="animation-delay: 0.2s"></div>
                                        <div class="w-2 h-2 rounded-full bg-teal-500 animate-bounce"
                                            style="animation-delay: 0.4s"></div>
                                    </div>
                                    <p class="text-sm text-center text-gray-500 dark:text-gray-400 mt-2">Uploading...
                                    </p>
                                </div>
                            </div>

                            <!-- Error messages -->
                            @error('tempBanners.*')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Settings Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-6 dark:text-white">Event Settings</h2>
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-sm uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                                Status</h3>
                            <p class="mt-2">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $event->status === 'Published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $event->status }}
                                </span>
                            </p>
                            <flux:separator class="mt-4" />
                        </div>
                        <div>
                            <h3 class="text-sm uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                                Currency</h3>
                            <p class="mt-2 text-base font-medium dark:text-white">{{ $event->currency }}</p>
                            <flux:separator class="mt-4" />
                        </div>
                        <div>
                            <h3 class="text-sm uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                                Timezone Conversion</h3>
                            <p class="mt-2 text-base font-medium dark:text-white">
                                {{ $event->auto_convert_timezone ? 'Enabled' : 'Disabled' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-2">
                    <flux:button wire:click="toggleEdit" variant="subtle">Cancel</flux:button>
                    <flux:button type="submit" variant="primary">Save Changes</flux:button>
                </div>
            </form>
        @else
            <!-- View Mode -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Basic Information Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-6 dark:text-white">Basic Information</h2>
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-sm uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                                Event Type</h3>
                            <p class="mt-2 text-base font-medium dark:text-white">{{ ucfirst($event->event_type) }}
                            </p>
                            <flux:separator class="mt-4" />
                        </div>
                        <div>
                            <h3 class="text-sm uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                                Venue</h3>
                            <p class="mt-2 text-base font-medium dark:text-white">
                                {{ $event->venue ?: 'Not specified' }}</p>

                            @if ($event->location)
                                <div class="mt-4">
                                    <div id="map" class="h-64 w-full rounded-lg relative">
                                        <!-- Map Placeholder -->
                                        <div id="map-placeholder"
                                            class="absolute inset-0 bg-gray-100 dark:bg-gray-700 rounded-lg flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-12 w-12 text-gray-400 dark:text-gray-500 mb-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <p class="text-gray-500 dark:text-gray-400 text-sm">Loading map...</p>
                                            <p class="text-gray-400 dark:text-gray-500 text-xs mt-1">
                                                {{ $event->venue }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <flux:separator class="mt-4" />
                        </div>
                        <div>
                            <h3 class="text-sm uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                                Category</h3>
                            <p class="mt-2 text-base font-medium dark:text-white">
                                {{ $event->category ?: 'Not specified' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Date and Time Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-6 dark:text-white">Date and Time</h2>
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-sm uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                                Event Date</h3>
                            <p class="mt-2 text-base font-medium dark:text-white">
                                {{ $event->start_datetime ? $event->start_datetime->format('M d, Y') : 'N/A' }}
                            </p>
                            <flux:separator class="mt-4" />
                        </div>
                        <div>
                            <h3 class="text-sm uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                                Time</h3>
                            <p class="mt-2 text-base font-medium dark:text-white">
                                {{ $event->start_datetime ? $event->start_datetime->format('h:i A') : 'N/A' }} -
                                {{ $event->end_datetime ? $event->end_datetime->format('h:i A') : 'N/A' }}
                            </p>
                            <flux:separator class="mt-4" />
                        </div>
                        <div>
                            <h3 class="text-sm uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                                Timezone</h3>
                            <p class="mt-2 text-base font-medium dark:text-white">{{ $event->timezone }}</p>
                            <flux:separator class="mt-4" />
                        </div>
                        <div>
                            <h3 class="text-sm uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                                Repeat</h3>
                            <p class="mt-2 text-base font-medium dark:text-white">{{ $event->event_repeat }}</p>
                            @if ($event->event_repeat !== 'Does not repeat' && $event->repeat_days)
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Number of days:
                                    {{ $event->repeat_days }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Settings Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-6 dark:text-white">Event Settings</h2>
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-sm uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                                Status</h3>
                            <p class="mt-2">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $event->status === 'Published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $event->status }}
                                </span>
                            </p>
                            <flux:separator class="mt-4" />
                        </div>
                        <div>
                            <h3 class="text-sm uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                                Currency</h3>
                            <p class="mt-2 text-base font-medium dark:text-white">{{ $event->currency }}</p>
                            <flux:separator class="mt-4" />
                        </div>
                        <div>
                            <h3 class="text-sm uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                                Timezone Conversion</h3>
                            <p class="mt-2 text-base font-medium dark:text-white">
                                {{ $event->auto_convert_timezone ? 'Enabled' : 'Disabled' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tickets Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-6 dark:text-white">Tickets</h2>
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-sm uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                                Active Tickets</h3>
                            <p class="mt-2 text-base font-medium dark:text-white">
                                {{ $event->tickets->where('status', 'active')->count() }} types
                            </p>
                            <flux:separator class="mt-4" />
                        </div>
                        <div>
                            <h3 class="text-sm uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                                Total Sold</h3>
                            <p class="mt-2 text-base font-medium dark:text-white">
                                {{ $event->tickets->sum('quantity_sold') }} tickets
                            </p>
                            <flux:separator class="mt-4" />
                        </div>
                        <div>
                            <h3 class="text-sm uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                                Revenue</h3>
                            <p class="mt-2 text-base font-medium dark:text-white">
                                {{ number_format($event->tickets->sum(function ($ticket) {return $ticket->quantity_sold * $ticket->price;}),2) }}
                                {{ $event->currency }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Event Details Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 md:col-span-3">
                    <h2 class="text-xl font-semibold mb-6 dark:text-white">Event Details</h2>
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-sm uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                                Description</h3>
                            <p class="mt-2 text-base dark:text-white">
                                {{ $event->description ?: 'No description provided' }}</p>
                            <flux:separator class="mt-4" />
                        </div>
                        <div>
                            <h3 class="text-sm uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                                Tags</h3>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach (json_decode($event->tags ?? '[]', true) as $tag)
                                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-full text-sm">
                                        {{ $tag }}
                                    </span>
                                @endforeach
                                @if (empty(json_decode($event->tags ?? '[]', true)))
                                    <span class="text-gray-500 dark:text-gray-400">No tags added</span>
                                @endif
                            </div>
                            <flux:separator class="mt-4" />
                        </div>
                        <div>
                            <h3 class="text-sm uppercase tracking-wider font-medium text-gray-500 dark:text-gray-400">
                                Banners</h3>
                            <div class="mt-2 space-y-2">
                                @forelse(json_decode($event->banners ?? '[]', true) as $banner)
                                    <div class="relative">
                                        <!-- Banner skeleton loader with preview -->
                                        <div class="relative w-full h-40 rounded-lg overflow-hidden">
                                            <!-- Skeleton loader -->
                                            <div
                                                class="absolute inset-0 skeleton-loader shimmer flex items-center justify-center">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-600 mb-2"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Loading
                                                        banner...</span>
                                                </div>
                                            </div>
                                            <!-- Image -->
                                            <img src="{{ $banner }}" alt="Event banner"
                                                class="w-full h-40 object-cover relative z-10"
                                                onload="this.style.opacity='1'; this.previousElementSibling.style.display='none';"
                                                onerror="this.style.display='none'; this.previousElementSibling.innerHTML='<div class=\'flex flex-col items-center\'><svg class=\'w-8 h-8 text-gray-400 dark:text-gray-600 mb-2\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z\' /></svg><span class=\'text-sm text-gray-500 dark:text-gray-400\'>Failed to load image</span></div>';"
                                                style="opacity: 0; transition: opacity 0.3s ease;" />
                                        </div>
                                    </div>
                                @empty
                                    <div
                                        class="flex items-center justify-center h-40 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                        <div class="text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No banners added
                                            </p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
    <link href="https://tiles.locationiq.com/v3/libs/maplibre-gl/1.15.2/maplibre-gl.css" rel="stylesheet" />
    <link href="https://tiles.locationiq.com/v3/css/liq-styles-ctrl-libre-gl.css?v=0.1.8" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://tiles.locationiq.com/v3/libs/gl-geocoder/4.5.1/locationiq-gl-geocoder.css?v=0.2.3"
        type="text/css" />

    <style>
        #map {
            width: 100%;
            height: 400px;
            border-radius: 0.5rem;
        }

        #map-placeholder {
            transition: opacity 0.3s ease;
        }

        .maplibregl-map {
            border-radius: 0.5rem;
        }

        /* Dark mode styles for the placeholder */
        .dark #map-placeholder {
            background-color: rgba(55, 65, 81, 1);
        }

        /* Shimmer effect for loaders */
        .shimmer {
            background: linear-gradient(90deg,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, 0.2) 50%,
                    rgba(255, 255, 255, 0) 100%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }

        .dark .shimmer {
            background: linear-gradient(90deg,
                    rgba(55, 65, 81, 0) 0%,
                    rgba(55, 65, 81, 0.2) 50%,
                    rgba(55, 65, 81, 0) 100%);
            background-size: 200% 100%;
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* Pulse animation for map loader */
        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .5;
            }
        }

        /* Skeleton loader for images */
        .skeleton-loader {
            background-color: #e5e7eb;
            position: relative;
            overflow: hidden;
        }

        .dark .skeleton-loader {
            background-color: #374151;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://tiles.locationiq.com/v3/libs/maplibre-gl/1.15.2/maplibre-gl.js"></script>
    <script src="https://tiles.locationiq.com/v3/js/liq-styles-ctrl-libre-gl.js?v=0.1.8"></script>
    <script src="https://tiles.locationiq.com/v3/libs/gl-geocoder/4.5.1/locationiq-gl-geocoder.min.js?v=0.2.3"></script>
    <script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.min.js"></script>

    <script>
        // Global initialization of LocationIQ
        window.initLocationIQ = function() {
            if (!window.locationiq) {
                window.locationiq = {
                    key: 'pk.8da423155473007977a90bb555d54b41',
                    getLayer: function(type) {
                        const styles = {
                            'Streets': 'https://tiles.locationiq.com/v3/streets/vector.json?key=' + this
                                .key,
                            'Dark': 'https://tiles.locationiq.com/v3/dark/vector.json?key=' + this.key,
                            'Light': 'https://tiles.locationiq.com/v3/light/vector.json?key=' + this.key
                        };
                        return styles[type] || styles['Streets'];
                    }
                };
            }
            return window.locationiq;
        };

        // Initialize LocationIQ globally
        initLocationIQ();

        (function() {
            let map = null;
            let marker = null;
            let initializationAttempts = 0;
            const MAX_ATTEMPTS = 5;

            function initializeMap() {
                // Check for both view mode and edit mode map containers
                const viewMapContainer = document.getElementById('map');
                const editMapContainer = document.getElementById('edit-map');

                // If neither map container exists, exit early
                if (!viewMapContainer && !editMapContainer) return;

                // Determine which map to initialize
                const isEditMode = !!editMapContainer;
                const mapContainer = isEditMode ? 'edit-map' : 'map';
                const mapPlaceholder = document.getElementById(isEditMode ? 'edit-map-placeholder' : 'map-placeholder');

                console.log(`Initializing map in ${isEditMode ? 'edit' : 'view'} mode`);

                @if ($event->location)
                    // Clear existing map instance if it exists
                    if (map) {
                        map.remove();
                        map = null;
                    }

                    // Make sure LocationIQ and maplibregl are defined
                    if (!window.locationiq || !window.maplibregl) {
                        console.log('LocationIQ or maplibregl not loaded yet, initializing LocationIQ...');
                        initLocationIQ();

                        // Retry initialization if libraries aren't loaded yet
                        if (initializationAttempts < MAX_ATTEMPTS) {
                            initializationAttempts++;
                            console.log(
                                `Retrying map initialization (attempt ${initializationAttempts}/${MAX_ATTEMPTS})...`
                            );
                            setTimeout(initializeMap, 500);
                            return;
                        } else {
                            console.error('Failed to initialize map after multiple attempts');
                            if (mapPlaceholder) {
                                mapPlaceholder.style.display = 'flex';
                                const errorText = mapPlaceholder.querySelector('p');
                                if (errorText) {
                                    errorText.textContent = 'Unable to load map libraries';
                                }
                            }
                            return;
                        }
                    }

                    // Reset attempt counter on successful library load
                    initializationAttempts = 0;

                    try {
                        // Set LocationIQ key
                        locationiq.key = 'pk.8da423155473007977a90bb555d54b41';

                        map = new maplibregl.Map({
                            container: mapContainer,
                            style: locationiq.getLayer("Streets"),
                            zoom: 15,
                            center: [{{ $event->location->longitude }}, {{ $event->location->latitude }}]
                        });

                        // Add marker
                        marker = new maplibregl.Marker({
                                color: '#FF0000'
                            })
                            .setLngLat([{{ $event->location->longitude }}, {{ $event->location->latitude }}])
                            .addTo(map);

                        // Add popup
                        const popup = new maplibregl.Popup({
                                offset: 25
                            })
                            .setHTML('<strong>{{ $event->venue }}</strong>');

                        marker.setPopup(popup);

                        // Add navigation controls
                        map.addControl(new maplibregl.NavigationControl(), 'top-right');

                        // Hide placeholder when map is loaded
                        map.on('load', function() {
                            if (mapPlaceholder) {
                                mapPlaceholder.style.display = 'none';
                            }
                        });

                        // Show placeholder if map errors
                        map.on('error', function() {
                            if (mapPlaceholder) {
                                mapPlaceholder.style.display = 'flex';
                                const errorText = mapPlaceholder.querySelector('p');
                                if (errorText) {
                                    errorText.textContent = 'Unable to load map';
                                }
                            }
                        });

                    } catch (error) {
                        console.error('Error initializing map:', error);
                        if (mapPlaceholder) {
                            mapPlaceholder.style.display = 'flex';
                            const errorText = mapPlaceholder.querySelector('p');
                            if (errorText) {
                                errorText.textContent = 'Unable to load map';
                            }
                        }
                    }
                @endif
            }

            // Initialize on first load
            document.addEventListener('DOMContentLoaded', initializeMap);

            // Handle Livewire navigation events
            document.addEventListener('livewire:navigated', initializeMap);

            // Also listen for Alpine.js initialization events
            document.addEventListener('alpine:initialized', initializeMap);

            // Listen for Livewire property updates that might affect the map
            document.addEventListener('livewire:update', () => {
                // Use a small delay to ensure DOM is updated
                setTimeout(initializeMap, 300);
            });

            // Listen specifically for edit mode toggle
            window.addEventListener('toggleEdit', () => {
                console.log('Edit mode toggled, reinitializing map...');
                // Use a delay to ensure the DOM is updated
                setTimeout(initializeMap, 500);
            });

            // Clean up map when navigating away
            document.addEventListener('livewire:navigating', () => {
                if (map) {
                    map.remove();
                    map = null;
                }
            });
        })();
    </script>

    <script>
        function venueSearch() {
            return {
                query: '',
                suggestions: [],
                isLoading: false,
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
