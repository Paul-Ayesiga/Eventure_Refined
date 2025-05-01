<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-black shadow rounded-lg p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-bold dark:text-white">Create New Event</h2>
        </div>

        <form wire:submit.prevent="store">
            <div>
                <flux:heading size="lg">Create Event</flux:heading>
                <flux:text class="mt-2">Fill in the details for your new event.</flux:text>
            </div>

            <!-- Event Type Selection -->
            <div>
                <flux:radio.group wire:model="eventType" label="Select the event type" variant="segmented" required>
                    <flux:radio label="Venue Event" value="venue" />
                    <flux:radio label="Online Event" value="online" />
                    <flux:radio label="Undecided" value="undecided" />
                </flux:radio.group>
                @error('eventType') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Event Name -->
            <div>
                <flux:label required>Event name</flux:label>
                <flux:input wire:model="name" placeholder="Enter event name" class="mt-2 w-full" />
                @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Venue Selection -->
            <div class="relative">
                <flux:label required>Select a venue</flux:label>
                <div class="relative mt-2">
                    <!-- Search Input -->
                    <div class="relative">
                        <input wire:model="venue" id="venue-search" type="text"
                            class="w-full px-4 py-2.5 pl-10 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent focus:border-accent dark:bg-black dark:border-gray-700 dark:text-gray-200"
                            placeholder="Search for a venue..." />
                        <!-- Search Icon -->
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Suggestions Dropdown -->
                    <div
                        class="w-full mt-1 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <!-- Individual Suggestion Template -->
                        <div class="hidden"><!-- This is a template, will be populated by jQuery autocomplete -->
                            <div
                                class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-700 last:border-0">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-200">
                                    <!-- Suggestion Name -->
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    <!-- Suggestion Address -->
                                </div>
                            </div>
                        </div>
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
                    <flux:select placeholder="Does not repeat" class="mt-1" />
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <flux:label>Start date</flux:label>
                        <flux:input wire:model="start_date" type="date" class="mt-1" />
                        @error('start_date') < </div>
                        </div>

                        @push('scripts')
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.devbridge-autocomplete/1.4.11/jquery.autocomplete.min.js">
                            </script>
                            <script>
                                $(document).ready(function() {
                                    var locationiqKey =
                                    "{{ \App\Helpers\LocationIQHelper::getJsApiKey() }}"; // LocationIQ API key from environment

                                    $('#venue-search').autocomplete({
                                        minChars: 3,
                                        deferRequestBy: 250,
                                        serviceUrl: 'https://api.locationiq.com/v1/autocomplete',
                                        paramName: 'q',
                                        params: {
                                            key: locationiqKey,
                                            format: "json",
                                            limit: 5
                                        },
                                        ajaxSettings: {
                                            dataType: 'json'
                                        },
                                        formatResult: function(suggestion, currentValue) {
                                            var format =
                                                '<div class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-700 last:border-0">' +
                                                '<div class="text-sm font-medium text-gray-900 dark:text-gray-200">' +
                                                highlight(suggestion.data.display_place, currentValue) +
                                                '</div>' +
                                                '<div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">' +
                                                highlight(suggestion.data.display_address, currentValue) +
                                                '</div>' +
                                                '</div>';
                                            return format;
                                        },
                                        transformResult: function(response) {
                                            var suggestions = $.map(response, function(dataItem) {
                                                return {
                                                    value: dataItem.display_name,
                                                    data: dataItem
                                                };
                                            });
                                            return {
                                                suggestions: suggestions
                                            };
                                        },
                                        onSelect: function(suggestion) {
                                            // Update the Livewire venue property with both display name and full data
                                            @this.set('venue', suggestion.data.display_name);
                                            @this.set('venueData', suggestion.data);
                                        }
                                    });

                                    function highlight(text, focus) {
                                        var r = RegExp('(' + escapeRegExp(focus) + ')', 'gi');
                                        return text.replace(r, '<strong>$1</strong>');

                                    }

                                    function escapeRegExp(str) {
                                        return str.replace(/[-[\]/{}()*+?.\\^$|]/g, '\\$&');
                                    }
                                });
                            </script>
                        @endpush
