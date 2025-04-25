<div>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <!-- Archived Warning Banner -->
        @if ($event->isArchived())
            <div
                class="mb-6 p-4 border-l-4 border-amber-500 bg-amber-50 dark:bg-amber-900/20 dark:border-amber-600 rounded-md">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500 dark:text-amber-400"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-amber-700 dark:text-amber-300">This event has been archived and is now
                            read-only. Bookings cannot be modified.</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-2">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Event Bookings</h2>
                @if ($event->isArchived())
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                        Archived
                    </span>
                @endif
            </div>
            <div class="flex flex-wrap gap-2">
                @if (!$event->isArchived())
                    <div class="flex space-x-2">
                        <flux:button icon="plus" wire:click="$set('isSimulating', true)" variant="primary"
                            class="bg-teal-500">
                            Simulate Purchase
                        </flux:button>
                    </div>
                @endif
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
                @if (!$event->isArchived())
                    <div class="mt-6">
                        <flux:button icon="plus" wire:click="$set('isSimulating', true)" variant="primary"
                            class="bg-teal-500">
                            Simulate Purchase
                        </flux:button>
                    </div>
                @endif
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
                                            @if (!$event->isArchived())
                                                <flux:modal.trigger :name="'delete-booking-'.$booking->id">
                                                    <flux:button variant="danger" icon="trash" size="sm"
                                                        title="Delete"></flux:button>
                                                </flux:modal.trigger>
                                            @endif
                                        </flux:button.group>
                                    </div>
                                </td>
                            </tr>
                            <flux:modal :name="'delete-booking-'.$booking->id" class="min-w-[22rem]">
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
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $booking->booking_reference }}
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
                                @if (!$event->isArchived())
                                    <flux:modal.trigger :name="'delete-booking-'.$booking->id">
                                        <flux:button variant="danger" icon="trash" size="sm" title="Delete">
                                        </flux:button>
                                    </flux:modal.trigger>
                                @endif
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

            <form
                wire:submit.prevent="{{ $useStripePayment && !$clientSecret ? 'initializePayment' : 'simulatePurchase' }}">
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
                            :max="$selectedTicketId ? $tickets->firstWhere('id', $selectedTicketId) ?->
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

                    <!-- Use Stripe Payment Toggle -->
                    <div class="flex items-center justify-between">
                        <div>
                            <flux:label>Use Stripe Payment</flux:label>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Process a real payment with Stripe</p>
                        </div>
                        <flux:switch wire:model.live="useStripePayment" />
                    </div>

                    <!-- Stripe Payment Form (shown when useStripePayment is true) -->
                    @if ($useStripePayment)
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Payment Information</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Secure payment via Stripe</p>
                            </div>

                            <!-- Payment will be processed for the selected customer -->
                            <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    <span class="font-medium">Payment will be processed for:</span>
                                    @if ($selectedCustomerId)
                                        {{ collect($customers)->firstWhere('id', $selectedCustomerId)['name'] }}
                                    @else
                                        Selected customer
                                    @endif
                                </p>
                            </div>

                            <!-- Payment form container with fixed structure -->
                            <div id="stripe-payment-container" class="relative">
                                @if ($clientSecret)
                                    <!-- Fixed payment element container -->
                                    <div id="payment-element-container">
                                        <div id="payment-element" class="mb-4"></div>
                                    </div>

                                    <!-- Fixed message container -->
                                    <div id="payment-message" class="text-sm text-red-500 mb-4 hidden"></div>

                                    <!-- Fixed button container -->
                                    <div class="flex justify-between">
                                        <flux:button variant="ghost" wire:click="cancelPayment">Cancel Payment
                                        </flux:button>
                                        <flux:button id="submit-payment" variant="primary">
                                            <span id="button-text">Pay Now</span>
                                            <span id="spinner" class="hidden">Processing...</span>
                                        </flux:button>
                                    </div>
                                @else
                                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-center">
                                        <p class="text-sm text-blue-800 dark:text-blue-200 mb-2">
                                            <span class="font-medium">Preparing payment form...</span>
                                        </p>
                                        <div class="flex justify-center">
                                            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500">
                                            </div>
                                        </div>
                                        <p class="text-xs text-blue-600 dark:text-blue-300 mt-2">
                                            Click "Initialize Payment" to start the payment process.
                                        </p>
                                    </div>
                                @endif
                            </div>
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
                        <flux:button type="submit" variant="primary">
                            @if ($useStripePayment && !$clientSecret)
                                Initialize Payment
                            @elseif ($useStripePayment && $clientSecret)
                                Create Booking
                            @else
                                Create Booking
                            @endif
                        </flux:button>
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

@push('scripts')
    @if (config('cashier.key'))
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            let stripe = null;
            let elements = null;
            let paymentElement = null;

            document.addEventListener('livewire:initialized', () => {
                // Initialize Stripe
                try {
                    stripe = Stripe('{{ config('cashier.key') }}');
                } catch (error) {
                    console.error('Error initializing Stripe:', error);
                }

                // Initialize variables
                let lastClientSecret = null;
                let lastTicketQuantity = @this.get('ticketQuantity');

                // Listen for payment intent created event
                @this.on('payment-intent-created', (data) => {
                    console.log('Payment intent created/updated event received');
                    const clientSecret = data.clientSecret;
                    lastClientSecret = clientSecret;

                    // Clear any existing elements first
                    if (paymentElement) {
                        try {
                            paymentElement.unmount();
                        } catch (e) {
                            console.log('Error unmounting payment element:', e);
                        }
                        paymentElement = null;
                    }

                    // Wait a moment for the DOM to update before setting up elements
                    setTimeout(() => {
                        setupStripeElements(clientSecret);
                    }, 500);
                });

                // Listen for ticket quantity changes
                @this.on('ticket-quantity-changed', (quantity) => {
                    console.log('Ticket quantity changed to:', quantity);
                    if (lastClientSecret) {
                        setTimeout(() => {
                            setupStripeElements(lastClientSecret);
                        }, 500);
                    }
                });

                // Listen for Livewire updates that might affect the payment form
                document.addEventListener('livewire:update', () => {
                    // Check if ticket quantity has changed
                    const currentQuantity = @this.get('ticketQuantity');

                    // Check if we need to reinitialize the payment form
                    if (lastClientSecret && currentQuantity !== lastTicketQuantity) {
                        console.log('Ticket quantity changed from', lastTicketQuantity, 'to', currentQuantity);
                        setTimeout(() => {
                            setupStripeElements(lastClientSecret);
                        }, 500);
                    }

                    // Update the last ticket quantity
                    lastTicketQuantity = currentQuantity;
                });
            });

            function setupStripeElements(clientSecret) {
                try {
                    console.log('Setting up Stripe Elements with ticket quantity:', @this.get('ticketQuantity'));

                    // Validate client secret
                    if (!clientSecret || typeof clientSecret !== 'string' || !clientSecret.startsWith('pi_')) {
                        console.error('Invalid client secret format');
                        return;
                    }

                    // First, clean up any existing elements
                    if (paymentElement) {
                        try {
                            paymentElement.unmount();
                        } catch (e) {
                            console.log('Error unmounting payment element:', e);
                        }
                        paymentElement = null;
                    }

                    if (elements) {
                        elements = null;
                    }

                    // Get the container
                    const container = document.getElementById('payment-element-container');
                    if (!container) {
                        console.error('Payment element container not found');
                        return;
                    }

                    // Create a fresh payment element div
                    container.innerHTML = '<div id="payment-element" class="mb-4"></div>';

                    // Create elements instance with the client secret
                    elements = stripe.elements({
                        clientSecret: clientSecret,
                        appearance: {
                            theme: document.documentElement.classList.contains('dark') ? 'night' : 'stripe',
                            variables: {
                                colorPrimary: '#10b981', // teal-500
                            }
                        }
                    });

                    // Create the payment element
                    paymentElement = elements.create('payment');

                    // Add event listeners
                    paymentElement.on('ready', function() {
                        console.log('Payment element is ready');
                    });

                    paymentElement.on('loaderror', function(event) {
                        console.error('Payment element load error:', event);
                    });

                    // Mount the element
                    setTimeout(() => {
                        try {
                            const mountElement = document.getElementById('payment-element');
                            if (mountElement) {
                                paymentElement.mount('#payment-element');
                                console.log('Payment element mounted successfully');
                            } else {
                                console.error('Payment element mount target not found');
                            }
                        } catch (e) {
                            console.error('Error mounting payment element:', e);
                        }
                    }, 100);

                    // Set up the submit button
                    const submitButton = document.getElementById('submit-payment');
                    if (submitButton) {
                        // Remove any existing event listeners
                        const newSubmitButton = submitButton.cloneNode(true);
                        submitButton.parentNode.replaceChild(newSubmitButton, submitButton);
                        newSubmitButton.addEventListener('click', handleSubmit);
                    } else {
                        console.error('Submit button not found!');
                    }
                } catch (error) {
                    console.error('Error setting up Stripe Elements:', error);
                }
            }

            async function handleSubmit(e) {
                e.preventDefault();

                // Check if elements is initialized
                if (!elements) {
                    console.error('Stripe Elements not initialized');
                    alert('Payment form not ready. Please try again.');
                    return;
                }

                setLoading(true);

                // Find or create message container
                let messageContainer = document.getElementById('payment-message');
                if (!messageContainer) {
                    // If container doesn't exist, try to find the payment element parent and add it
                    const paymentElement = document.getElementById('payment-element');
                    if (paymentElement && paymentElement.parentNode) {
                        messageContainer = document.createElement('div');
                        messageContainer.id = 'payment-message';
                        messageContainer.className = 'text-sm text-red-500 mb-4 hidden';
                        paymentElement.parentNode.insertBefore(messageContainer, paymentElement.nextSibling);
                    } else {
                        console.error('Cannot create message container - payment element not found');
                    }
                }

                if (messageContainer) {
                    messageContainer.classList.add('hidden');
                    messageContainer.textContent = '';
                }

                try {
                    const {
                        error
                    } = await stripe.confirmPayment({
                        elements,
                        confirmParams: {
                            return_url: window.location.href,
                        },
                        redirect: 'if_required'
                    });

                    if (error) {
                        console.error('Payment error:', error.message);
                        // Show error message
                        if (messageContainer) {
                            messageContainer.textContent = error.message;
                            messageContainer.classList.remove('hidden');
                        } else {
                            alert('Payment error: ' + error.message);
                        }
                        setLoading(false);
                    } else {
                        // Payment succeeded
                        setLoading(false);
                        @this.call('handlePaymentSuccess');
                    }
                } catch (exception) {
                    console.error('Exception during payment confirmation:', exception);
                    if (messageContainer) {
                        messageContainer.textContent = 'An unexpected error occurred. Please try again.';
                        messageContainer.classList.remove('hidden');
                    } else {
                        alert('An unexpected error occurred. Please try again.');
                    }
                    setLoading(false);
                }
            }

            function setLoading(isLoading) {
                const submitButton = document.getElementById('submit-payment');
                if (!submitButton) {
                    console.error('Submit button not found in setLoading');
                    return;
                }

                // Find or create spinner and button text elements
                let spinner = document.getElementById('spinner');
                let buttonText = document.getElementById('button-text');

                if (!spinner || !buttonText) {
                    // If elements don't exist, create them
                    if (!spinner) {
                        spinner = document.createElement('span');
                        spinner.id = 'spinner';
                        spinner.className = 'hidden';
                        spinner.textContent = 'Processing...';
                        submitButton.appendChild(spinner);
                    }

                    if (!buttonText) {
                        buttonText = document.createElement('span');
                        buttonText.id = 'button-text';
                        buttonText.textContent = 'Pay Now';
                        submitButton.appendChild(buttonText);
                    }
                }

                if (isLoading) {
                    submitButton.disabled = true;
                    spinner.classList.remove('hidden');
                    buttonText.classList.add('hidden');
                } else {
                    submitButton.disabled = false;
                    spinner.classList.add('hidden');
                    buttonText.classList.remove('hidden');
                }
            }
        </script>
    @endif
@endpush
