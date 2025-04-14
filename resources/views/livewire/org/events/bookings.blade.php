<div>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Event Bookings</h2>
            <div class="flex flex-wrap gap-2">
                <div class="flex space-x-2">
                    <flux:button icon="plus" wire:click="$set('isSimulating', true)" variant="primary"
                        class="bg-teal-500">
                        Simulate Purchase
                    </flux:button>
                </div>
                <div class="flex space-x-2">
                    <flux:button icon="arrow-down-tray" variant="outline" class="whitespace-nowrap">
                        <span class="hidden sm:inline">Export</span>
                    </flux:button>
                    <flux:button icon="funnel" variant="outline" class="whitespace-nowrap">
                        <span class="hidden sm:inline">Filter</span>
                    </flux:button>
                </div>
            </div>
        </div>

        @if ($bookings->isEmpty())
            <div class="text-center py-8">
                <flux:icon name="ticket" class="mx-auto h-12 w-12 text-gray-400" />
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No bookings yet</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by simulating a purchase or wait
                    for customers to book tickets.</p>
                <div class="mt-6">
                    <flux:button icon="plus" wire:click="$set('isSimulating', true)" variant="primary"
                        class="bg-teal-500">
                        Simulate Purchase
                    </flux:button>
                </div>
            </div>
        @else
            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Booking ID</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Customer</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tickets</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Total Amount</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Date</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($bookings as $booking)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">#{{ $booking->id }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $booking->booking_reference }}</div>
                                    @if ($booking->event_date)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Event date:
                                            {{ \Carbon\Carbon::parse($booking->event_date)->format('M d, Y') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($booking->attendees->isNotEmpty())
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $booking->attendees->first()->first_name }}
                                            {{ $booking->attendees->first()->last_name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $booking->attendees->first()->email }}
                                        </div>
                                        @if ($booking->attendees->first()->phone)
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $booking->attendees->first()->phone }}
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-sm text-gray-500 dark:text-gray-400">No attendees</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $booking->tickets->sum('pivot.quantity') }} tickets
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        @foreach ($booking->tickets as $ticket)
                                            {{ $ticket->name }} ({{ $ticket->pivot->quantity }})<br>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ number_format($booking->total_amount, 2) }} {{ $event->currency }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $booking->created_at->format('M d, Y') }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $booking->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <flux:button.group>
                                            <flux:button size="sm" icon="eye"
                                                wire:click="viewBooking({{ $booking->id }})" variant="primary"
                                                title="View Details">
                                            </flux:button>
                                            <flux:button size="sm" icon="printer"
                                                wire:click="printBooking({{ $booking->id }})" variant="outline"
                                                title="Print">
                                            </flux:button>
                                            <flux:modal.trigger :name="'delete-booking-'.$booking-> id">
                                                <flux:button variant="danger" icon="trash" size="sm"
                                                    title="Delete"></flux:button>
                                            </flux:modal.trigger>
                                        </flux:button.group>
                                    </div>
                                </td>
                            </tr>
                            <flux:modal :name="'delete-booking-'.$booking-> id" class="min-w-[22rem]">
                                <div class="space-y-6">
                                    <div>
                                        <flux:heading size="lg">Delete Booking?</flux:heading>

                                        <flux:text class="mt-2">
                                            <p>You're about to delete this booking.</p>
                                            <p>This action cannot be reversed.</p>
                                        </flux:text>
                                    </div>

                                    <div class="flex gap-2">
                                        <flux:spacer />

                                        <flux:modal.close>
                                            <flux:button variant="ghost">Cancel</flux:button>
                                        </flux:modal.close>

                                        <flux:button variant="danger" wire:click="deleteBooking({{ $booking->id }})"
                                            class="cursor-pointer">Delete Booking</flux:button>
                                    </div>
                                </div>
                            </flux:modal>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden space-y-4">
                @foreach ($bookings as $booking)
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">#{{ $booking->id }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $booking->booking_reference }}
                                </div>
                            </div>
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>

                        <div class="mt-4 space-y-3">
                            <!-- Customer Information -->
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">Customer</div>
                                @if ($booking->attendees->isNotEmpty())
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $booking->attendees->first()->first_name }}
                                        {{ $booking->attendees->first()->last_name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $booking->attendees->first()->email }}
                                    </div>
                                    @if ($booking->attendees->first()->phone)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $booking->attendees->first()->phone }}
                                        </div>
                                    @endif
                                @else
                                    <div class="text-sm text-gray-500 dark:text-gray-400">No attendees</div>
                                @endif
                            </div>

                            <!-- Tickets Information -->
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">Tickets</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $booking->tickets->sum('pivot.quantity') }} tickets
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    @foreach ($booking->tickets as $ticket)
                                        {{ $ticket->name }} ({{ $ticket->pivot->quantity }})<br>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Amount and Date -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Total Amount</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ number_format($booking->total_amount, 2) }} {{ $event->currency }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Date</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $booking->created_at->format('M d, Y h:i A') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-4 flex justify-end space-x-2">
                            <flux:button.group>
                                <flux:button size="sm" icon="eye"
                                    wire:click="viewBooking({{ $booking->id }})" variant="primary"
                                    title="View Details">
                                </flux:button>
                                <flux:button size="sm" icon="printer"
                                    wire:click="printBooking({{ $booking->id }})" variant="outline" title="Print">
                                </flux:button>
                                <flux:modal.trigger :name="'delete-booking-'.$booking-> id">
                                    <flux:button variant="danger" icon="trash" size="sm" title="Delete">
                                    </flux:button>
                                </flux:modal.trigger>
                            </flux:button.group>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

    <!-- Simulation Modal -->
    <flux:modal wire:model="isSimulating" class="max-w-md">
        <div class="p-6">
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Simulate Purchase
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Create test bookings to simulate customer purchases.
                </p>
            </div>

            <form wire:submit.prevent="simulatePurchase">
                <div class="space-y-4">
                    <!-- Customer Selection -->
                    <div>
                        <flux:label for="selectedCustomerId" required>Select Customer</flux:label>
                        <flux:select id="selectedCustomerId" wire:model="selectedCustomerId" class="mt-1 w-full">
                            <option value="">Select a customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer['id'] }}">
                                    {{ $customer['name'] }} - {{ $customer['email'] }}
                                    @if ($customer['phone'])
                                        ({{ $customer['phone'] }})
                                    @endif
                                </option>
                            @endforeach
                        </flux:select>
                        @error('selectedCustomerId')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Ticket Selection -->
                    <div>
                        <flux:label for="selectedTicketId" required>Select Ticket</flux:label>
                        <flux:select id="selectedTicketId" wire:model.live="selectedTicketId" class="mt-1 w-full">
                            <option value="">Select a ticket</option>
                            @foreach ($tickets as $ticket)
                                <option value="{{ $ticket->id }}">
                                    {{ $ticket->name }} - {{ number_format($ticket->price, 2) }}
                                    {{ $event->currency }}
                                    @if ($ticket->max_tickets_per_booking)
                                        (Max {{ $ticket->max_tickets_per_booking }} per booking)
                                    @endif
                                </option>
                            @endforeach
                        </flux:select>
                        @error('selectedTicketId')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Event Date Selection - Only show if event repeats AND selected ticket is not a repeating ticket -->
                    @if (count($eventDates) > 1 && !$selectedTicketRepeats && $selectedTicketId)
                        <div>
                            <flux:label for="selectedEventDates" required>Select Event Dates</flux:label>
                            <div class="mt-1 grid grid-cols-2 gap-2">
                                @foreach ($eventDates as $dateValue => $dateLabel)
                                    <label
                                        class="flex items-center p-2 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <input type="checkbox" wire:model.live="selectedEventDates"
                                            value="{{ $dateValue }}" class="mr-2">
                                        <span>{{ $dateLabel }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                This ticket is only valid for the selected dates. Select at least one date.
                            </p>
                            @error('selectedEventDates')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    @elseif (count($eventDates) > 1 && $selectedTicketRepeats && $selectedTicketId)
                        <div>
                            <div class="p-3 bg-blue-50 dark:bg-blue-900 rounded-lg">
                                <p class="text-sm text-blue-800 dark:text-blue-200">
                                    <span class="font-medium">Note:</span> This ticket is valid for all event dates
                                    ({{ count($eventDates) }} days).
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Quantity -->
                    <div>
                        <flux:label for="ticketQuantity" required>Quantity</flux:label>
                        <flux:input id="ticketQuantity" type="number" wire:model.live="ticketQuantity"
                            class="mt-1 w-full" min="1"
                            :max="$selectedTicketId ? $tickets-> firstWhere('id', $selectedTicketId) ?->
                                max_tickets_per_booking : null" />
                        @error('ticketQuantity')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Use Customer as Attendee Toggle -->
                    <div class="flex items-center justify-between">
                        <div>
                            <flux:label>Use Customer as Attendee</flux:label>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Toggle to manually enter attendee
                                information</p>
                        </div>
                        <flux:switch wire:model.live="useCustomerAsAttendee" />
                    </div>

                    <!-- Manual Attendee Information (shown only when useCustomerAsAttendee is false) -->
                    @if (!$useCustomerAsAttendee)
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Attendee Information</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ count($attendees) }}
                                    attendee(s) for {{ $ticketQuantity }} ticket(s)</p>
                            </div>

                            @foreach ($attendees as $index => $attendee)
                                <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex justify-between items-center mb-3">
                                        <h5 class="text-sm font-medium text-gray-900 dark:text-white">Attendee
                                            #{{ $index + 1 }}</h5>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- First Name -->
                                        <div>
                                            <flux:label for="attendees.{{ $index }}.first_name" required>First
                                                Name</flux:label>
                                            <flux:input id="attendees.{{ $index }}.first_name"
                                                wire:model="attendees.{{ $index }}.first_name"
                                                class="mt-1 w-full" />
                                            @error("attendees.{$index}.first_name")
                                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Last Name -->
                                        <div>
                                            <flux:label for="attendees.{{ $index }}.last_name" required>Last
                                                Name</flux:label>
                                            <flux:input id="attendees.{{ $index }}.last_name"
                                                wire:model="attendees.{{ $index }}.last_name"
                                                class="mt-1 w-full" />
                                            @error("attendees.{$index}.last_name")
                                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <!-- Email -->
                                        <div>
                                            <flux:label for="attendees.{{ $index }}.email" required>Email
                                            </flux:label>
                                            <flux:input id="attendees.{{ $index }}.email" type="email"
                                                wire:model="attendees.{{ $index }}.email"
                                                class="mt-1 w-full" />
                                            @error("attendees.{$index}.email")
                                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <!-- Phone -->
                                        <div>
                                            <flux:label for="attendees.{{ $index }}.phone">Phone</flux:label>
                                            <flux:input id="attendees.{{ $index }}.phone"
                                                wire:model="attendees.{{ $index }}.phone"
                                                class="mt-1 w-full" />
                                            @error("attendees.{$index}.phone")
                                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">The number of attendees will
                                automatically adjust based on the ticket quantity.</p>
                        </div>
                    @endif

                    <!-- Join Waiting List -->
                    <div class="flex items-center justify-between">
                        <div>
                            <flux:label>Join Waiting List</flux:label>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Get notified when tickets become
                                available</p>
                        </div>
                        <flux:switch wire:model="joinWaitingList" />
                    </div>

                    <div class="flex justify-end gap-2">
                        <flux:button variant="ghost" wire:click="$set('isSimulating', false)">Cancel</flux:button>
                        <flux:button type="submit" variant="primary">Create Booking</flux:button>
                    </div>
                </div>
            </form>
        </div>
    </flux:modal>

    <!-- Booking Details Modal -->
    <flux:modal wire:model="isModalOpen" class="max-w-3xl" variant="flyout">
        <div class="p-6">
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Booking Details
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    View and manage booking information.
                </p>
            </div>

            @if ($selectedBooking)
                <div class="space-y-6">
                    <!-- Booking Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Booking ID</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">#{{ $selectedBooking->id }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $selectedBooking->booking_reference }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Booking Date</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $selectedBooking->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h4>
                            <p class="mt-1">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $selectedBooking->status === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' }}">
                                    {{ ucfirst($selectedBooking->status) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Amount</h4>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ number_format($selectedBooking->total_amount, 2) }} {{ $event->currency }}</p>
                        </div>
                    </div>

                    <!-- Event Dates -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Event Dates</h4>
                        <div class="mt-2">
                            @if ($selectedBooking->dates->isNotEmpty())
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($selectedBooking->dates as $date)
                                        <span
                                            class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 rounded-full">
                                            {{ \Carbon\Carbon::parse($date->event_date)->format('M d, Y') }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400">No specific dates</p>
                            @endif
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Attendees</h4>
                        <div class="mt-2 space-y-4">
                            @forelse($selectedBooking->attendees as $attendee)
                                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $attendee->first_name }} {{ $attendee->last_name }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $attendee->email }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-900 dark:text-white">
                                                {{ $attendee->phone ?? 'No phone provided' }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Ticket: {{ $attendee->ticket->name }}
                                            </p>
                                            <div class="mt-2">
                                                <flux:button size="xs" icon="ticket" variant="outline"
                                                    wire:click="generateTicket({{ $attendee->id }})">
                                                    Generate Ticket
                                                </flux:button>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($attendee->custom_fields)
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Additional Information:
                                            </p>
                                            <ul class="text-sm text-gray-900 dark:text-white">
                                                @foreach (json_decode($attendee->custom_fields, true) as $key => $value)
                                                    <li>{{ ucfirst($key) }}: {{ $value }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    @if ($attendee->check_in_status)
                                        <div class="mt-2">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                Checked in at {{ $attendee->check_in_time->format('M d, Y h:i A') }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    No attendees found for this booking.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Tickets Information -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tickets</h4>
                        <div class="mt-2 space-y-4">
                            @foreach ($selectedBooking->tickets as $ticket)
                                <div
                                    class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $ticket->name }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Quantity:
                                            {{ $ticket->pivot->quantity }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ number_format($ticket->price * $ticket->pivot->quantity, 2) }}
                                            {{ $event->currency }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ number_format($ticket->price, 2) }} each</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <flux:button type="button" wire:click="closeModal" variant="outline">
                        Close
                    </flux:button>
                    <flux:button type="button" icon="printer" variant="outline"
                        wire:click="printBooking({{ $selectedBooking->id }})">
                        Print
                    </flux:button>
                    <flux:button type="button" icon="ticket" variant="primary"
                        wire:click="generateTickets({{ $selectedBooking->id }})">
                        Generate Tickets
                    </flux:button>
                </div>
            @endif
        </div>
    </flux:modal>
</div>
