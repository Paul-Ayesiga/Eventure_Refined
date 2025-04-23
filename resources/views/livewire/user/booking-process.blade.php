<div class="bg-gray-100 dark:bg-gray-900 min-h-screen pb-12">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-md">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <!-- Back Button -->
                <a href="{{ route('user.event.detail', $eventId) }}"
                    class="inline-flex items-center text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Event
                </a>

                <!-- Event Title -->
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">{{ $event->name }}</h1>

                <!-- Empty div for spacing -->
                <div class="w-24"></div>
            </div>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-8">
            <div class="flex-1">
                <div class="flex items-center">
                    <div
                        class="w-8 h-8 rounded-full flex items-center justify-center {{ $currentStep >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-300 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">
                        1
                    </div>
                    <div class="ml-2">
                        <p
                            class="text-sm font-medium {{ $currentStep >= 1 ? 'text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400' }}">
                            Attendee Information</p>
                    </div>
                </div>
            </div>

            <div class="flex-1 flex justify-center">
                <div class="h-1 w-full bg-gray-300 dark:bg-gray-700 self-center"></div>
            </div>

            <div class="flex-1">
                <div class="flex items-center">
                    <div
                        class="w-8 h-8 rounded-full flex items-center justify-center {{ $currentStep >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-300 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">
                        2
                    </div>
                    <div class="ml-2">
                        <p
                            class="text-sm font-medium {{ $currentStep >= 2 ? 'text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400' }}">
                            Review Order</p>
                    </div>
                </div>
            </div>

            <div class="flex-1 flex justify-center">
                <div class="h-1 w-full bg-gray-300 dark:bg-gray-700 self-center"></div>
            </div>

            <div class="flex-1 flex justify-end">
                <div class="flex items-center">
                    <div
                        class="w-8 h-8 rounded-full flex items-center justify-center {{ $currentStep >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-300 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">
                        3
                    </div>
                    <div class="ml-2">
                        <p
                            class="text-sm font-medium {{ $currentStep >= 3 ? 'text-gray-900 dark:text-white' : 'text-gray-500 dark:text-gray-400' }}">
                            Payment</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Section -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <!-- Step 1: Attendee Information -->
                    @if ($currentStep === 1)
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Attendee Information</h2>

                        <div class="space-y-6">
                            @foreach ($attendees as $index => $attendee)
                                <div class="border dark:border-gray-700 rounded-lg p-4">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">
                                        Attendee {{ $index + 1 }} - {{ $attendee['ticket_name'] }}
                                    </h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="attendee-{{ $index }}-first-name"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First
                                                Name</label>
                                            <input type="text" id="attendee-{{ $index }}-first-name"
                                                wire:model="attendees.{{ $index }}.first_name"
                                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                            @error("attendees.{$index}.first_name")
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="attendee-{{ $index }}-last-name"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last
                                                Name</label>
                                            <input type="text" id="attendee-{{ $index }}-last-name"
                                                wire:model="attendees.{{ $index }}.last_name"
                                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                            @error("attendees.{$index}.last_name")
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="attendee-{{ $index }}-email"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                            <input type="email" id="attendee-{{ $index }}-email"
                                                wire:model="attendees.{{ $index }}.email"
                                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                            @error("attendees.{$index}.email")
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="attendee-{{ $index }}-phone"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone
                                                (Optional)
                                            </label>
                                            <input type="tel" id="attendee-{{ $index }}-phone"
                                                wire:model="attendees.{{ $index }}.phone"
                                                class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                            @error("attendees.{$index}.phone")
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Step 2: Review Order -->
                    @elseif($currentStep === 2)
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Review Your Order</h2>

                        <!-- Event Details -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Event Details</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mr-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800 dark:text-white">{{ $event->name }}</h4>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            {{ \Carbon\Carbon::parse($selectedDate)->format('l, F d, Y') }}</p>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            {{ \Carbon\Carbon::parse($event->start_datetime)->format('g:i A') }} -
                                            {{ \Carbon\Carbon::parse($event->end_datetime)->format('g:i A') }}</p>
                                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                                            @if ($event->event_type === 'Online Event')
                                                Online Event
                                            @elseif($event->location)
                                                {{ $event->location->display_place }}
                                            @else
                                                {{ $event->venue ?? 'Location TBD' }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Attendee Summary -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Attendees</h3>
                            <div class="space-y-3">
                                @foreach ($attendees as $index => $attendee)
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                        <div class="flex justify-between">
                                            <div>
                                                <h4 class="font-medium text-gray-800 dark:text-white">
                                                    {{ $attendee['first_name'] }} {{ $attendee['last_name'] }}</h4>
                                                <p class="text-gray-600 dark:text-gray-300">{{ $attendee['email'] }}
                                                </p>
                                                @if (!empty($attendee['phone']))
                                                    <p class="text-gray-600 dark:text-gray-300">
                                                        {{ $attendee['phone'] }}</p>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                                    {{ $attendee['ticket_name'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Step 3: Payment -->
                    @elseif($currentStep === 3)
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Payment Information</h2>

                        <!-- Payment Method Selection -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Payment Method</h3>
                            <div class="flex space-x-4">
                                <label
                                    class="flex items-center p-4 border rounded-lg cursor-pointer {{ $paymentMethod === 'credit_card' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-300 dark:border-gray-700' }}">
                                    <input type="radio" wire:model.live="paymentMethod" value="credit_card"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-gray-800 dark:text-white">Credit Card</span>
                                </label>

                                <label
                                    class="flex items-center p-4 border rounded-lg cursor-pointer {{ $paymentMethod === 'paypal' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-300 dark:border-gray-700' }}">
                                    <input type="radio" wire:model.live="paymentMethod" value="paypal"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-gray-800 dark:text-white">PayPal</span>
                                </label>
                            </div>
                            @error('paymentMethod')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Credit Card Information (shown only if credit card is selected) -->
                        @if ($paymentMethod === 'credit_card')
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Card Details</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label for="card-number"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Card
                                            Number</label>
                                        <input type="text" id="card-number" wire:model="cardNumber"
                                            placeholder="1234 5678 9012 3456"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        @error('cardNumber')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="card-expiry"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Expiry
                                            Date</label>
                                        <input type="text" id="card-expiry" wire:model="cardExpiry"
                                            placeholder="MM/YY"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        @error('cardExpiry')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="card-cvc"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CVC</label>
                                        <input type="text" id="card-cvc" wire:model="cardCvc" placeholder="123"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        @error('cardCvc')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Billing Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Billing Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label for="billing-name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full
                                        Name</label>
                                    <input type="text" id="billing-name" wire:model="billingName"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    @error('billingName')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="billing-email"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                    <input type="email" id="billing-email" wire:model="billingEmail"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    @error('billingEmail')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="billing-phone"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                                    <input type="tel" id="billing-phone" wire:model="billingPhone"
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    @error('billingPhone')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Navigation Buttons -->
                    <div class="mt-8 flex justify-between">
                        @if ($currentStep > 1)
                            <button wire:click="prevStep"
                                class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Previous
                            </button>
                        @else
                            <div></div>
                        @endif

                        @if ($currentStep < $totalSteps)
                            <button wire:click="nextStep"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Next
                            </button>
                        @else
                            <button wire:click="completeBooking"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Complete Purchase
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-4">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Order Summary</h2>

                    <!-- Event Info -->
                    <div class="mb-4 pb-4 border-b dark:border-gray-700">
                        <h3 class="font-semibold text-gray-800 dark:text-white mb-1">{{ $event->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($selectedDate)->format('D, M d, Y') }}</p>
                    </div>

                    <!-- Tickets -->
                    <div class="mb-4 pb-4 border-b dark:border-gray-700">
                        <h3 class="font-semibold text-gray-800 dark:text-white mb-2">Tickets</h3>
                        <div class="space-y-2">
                            @foreach ($tickets as $ticket)
                                @if ($selectedTickets[$ticket->id] > 0)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">{{ $ticket->name }} x
                                            {{ $selectedTickets[$ticket->id] }}</span>
                                        <span class="font-medium text-gray-800 dark:text-white">{{ $event->currency }}
                                            {{ number_format($ticket->price * $selectedTickets[$ticket->id], 2) }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="mb-4">
                        <div class="flex justify-between font-bold">
                            <span class="text-gray-800 dark:text-white">Total</span>
                            <span class="text-gray-800 dark:text-white">{{ $event->currency }}
                                {{ number_format($totalPrice, 2) }}</span>
                        </div>
                    </div>

                    <!-- Secure Checkout Notice -->
                    <div class="mt-6 text-center">
                        <div class="flex justify-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Secure Checkout</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
