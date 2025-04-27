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
                        <p class="text-sm text-amber-700 dark:text-amber-300">This event has been archived and is no
                            longer available for booking. You will be redirected to the event details page.</p>
                    </div>
                </div>
            </div>
        @endif
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
                                            <flux:label for="attendee-{{ $index }}-first-name" required>First
                                                Name <span class="text-red-500">*</span></flux:label>
                                            <flux:input id="attendee-{{ $index }}-first-name"
                                                wire:model="attendees.{{ $index }}.first_name" />
                                            @error("attendees.{$index}.first_name")
                                                <flux:error>{{ $message }}</flux:error>
                                            @enderror
                                        </div>

                                        <div>
                                            <flux:label for="attendee-{{ $index }}-last-name" required>Last Name
                                                <span class="text-red-500">*</span>
                                            </flux:label>
                                            <flux:input id="attendee-{{ $index }}-last-name"
                                                wire:model="attendees.{{ $index }}.last_name" />
                                            @error("attendees.{$index}.last_name")
                                                <flux:error>{{ $message }}</flux:error>
                                            @enderror
                                        </div>

                                        <div>
                                            <flux:label for="attendee-{{ $index }}-email" required>Email
                                                <span class="text-red-500">*</span>
                                            </flux:label>
                                            <flux:input type="email" id="attendee-{{ $index }}-email"
                                                wire:model="attendees.{{ $index }}.email" />
                                            @error("attendees.{$index}.email")
                                                <flux:error>{{ $message }}</flux:error>
                                            @enderror
                                        </div>

                                        <div>
                                            <flux:label for="attendee-{{ $index }}-phone">Phone (Optional)
                                            </flux:label>
                                            <flux:input type="tel" id="attendee-{{ $index }}-phone"
                                                wire:model="attendees.{{ $index }}.phone" />
                                            @error("attendees.{$index}.phone")
                                                <flux:error>{{ $message }}</flux:error>
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
                            <div class="space-y-4">
                                <!-- Credit Card Option -->
                                <label
                                    class="flex items-start p-4 border rounded-lg cursor-pointer {{ $paymentMethod === 'credit_card' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-300 dark:border-gray-700' }}">
                                    <div class="flex items-center h-5">
                                        <input type="radio" wire:model.live="paymentMethod" value="credit_card"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <span class="font-medium text-gray-800 dark:text-white">Credit Card</span>
                                        <p class="text-gray-500 dark:text-gray-400">Pay securely with your credit or
                                            debit card</p>
                                    </div>
                                </label>

                                <!-- PayPal Option -->
                                <label
                                    class="flex items-start p-4 border rounded-lg cursor-pointer {{ $paymentMethod === 'paypal' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-300 dark:border-gray-700' }}">
                                    <div class="flex items-center h-5">
                                        <input type="radio" wire:model.live="paymentMethod" value="paypal"
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <span class="font-medium text-gray-800 dark:text-white">PayPal</span>
                                        <p class="text-gray-500 dark:text-gray-400">Pay using your PayPal account</p>
                                    </div>
                                </label>

                                <!-- Additional payment options will be added here -->

                                <script>
                                    // Initialize Stripe immediately when this section loads
                                    (function() {
                                        if (@json($currentStep) === 3 && @json($paymentMethod) === 'credit_card') {
                                            console.log('Payment step loaded with credit card selected, initializing payment');
                                            // Execute immediately and also after a short delay to ensure DOM is ready
                                            try {
                                                @this.call('createPaymentIntent');
                                            } catch (e) {
                                                console.error('Error calling createPaymentIntent:', e);
                                            }

                                            window.setTimeout(function() {
                                                try {
                                                    @this.call('createPaymentIntent');
                                                } catch (e) {
                                                    console.error('Error calling createPaymentIntent (delayed):', e);
                                                }
                                            }, 500);
                                        }
                                    })();
                                </script>
                            </div>
                            @error('paymentMethod')
                                <div class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stripe Payment Element -->
                        @if ($paymentMethod === 'credit_card')
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Card Details</h3>

                                <!-- Stripe Payment Container with fixed structure -->
                                <div id="stripe-payment-container" class="relative">
                                    <!-- Fixed payment element container -->
                                    <div id="payment-element-container" class="mb-4">
                                        <div id="payment-element" class="mb-4"></div>
                                    </div>

                                    <!-- Fixed message container -->
                                    <div id="payment-message" class="text-sm text-red-500 mb-4 hidden"></div>

                                    <!-- Payment button -->
                                    <div class="mt-4">
                                        <button type="button" id="submit-payment"
                                            class="w-full px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            Complete Purchase
                                        </button>

                                        <!-- Fallback option if Stripe is having issues -->
                                        <div class="mt-4 text-center">
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Having trouble
                                                with the payment form?</p>
                                            <button type="button" wire:click="completeBooking(true)"
                                                class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 underline">
                                                Try M-Pesa payment instead
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Debug message for development -->
                                    @if (config('app.debug'))
                                        <div id="stripe-loading-message"
                                            class="mt-2 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-center">
                                            <p class="text-sm text-blue-800 dark:text-blue-200 mb-2">
                                                <span class="font-medium">Preparing payment form...</span>
                                            </p>
                                            <div class="flex justify-center">
                                                <div
                                                    class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500">
                                                </div>
                                            </div>
                                            <p class="text-xs text-blue-600 dark:text-blue-300 mt-2">
                                                If the payment form doesn't appear,
                                                <button type="button" class="text-blue-500 underline"
                                                    id="retry-stripe-button">
                                                    click here to try again
                                                </button>
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @elseif ($paymentMethod === 'mpesa')
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">M-Pesa Payment
                                </h3>

                                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg mb-4">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-green-700 dark:text-green-300">
                                                Enter your M-Pesa phone number below. You will receive a prompt on your
                                                phone to complete the payment.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional payment form fields will be added here -->
                            </div>
                        @endif

                        <!-- Billing Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Billing Information
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <flux:label for="billing-name" required>Full Name <span
                                            class="text-red-500">*</span></flux:label>
                                    <flux:input id="billing-name" wire:model="billingName" />
                                    @error('billingName')
                                        <flux:error>{{ $message }}</flux:error>
                                    @enderror
                                </div>

                                <div>
                                    <flux:label for="billing-email" required>Email <span class="text-red-500">*</span>
                                    </flux:label>
                                    <flux:input type="email" id="billing-email" wire:model="billingEmail" />
                                    @error('billingEmail')
                                        <flux:error>{{ $message }}</flux:error>
                                    @enderror
                                </div>

                                <div>
                                    <flux:label for="billing-phone" required>Phone <span class="text-red-500">*</span>
                                    </flux:label>
                                    <flux:input type="tel" id="billing-phone" wire:model="billingPhone" />
                                    @error('billingPhone')
                                        <flux:error>{{ $message }}</flux:error>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Navigation Buttons -->
                    <div class="mt-8 flex justify-between">
                        @if ($currentStep > 1)
                            <flux:button wire:click="prevStep" variant="ghost">
                                Previous
                            </flux:button>
                        @else
                            <div></div>
                        @endif

                        @if ($currentStep < $totalSteps)
                            <flux:button wire:click="nextStep" variant="primary">
                                Next
                            </flux:button>
                        @else
                            @if ($paymentMethod === 'credit_card')
                                <!-- No button here - using the one in the Stripe payment form -->
                                <div></div>
                                <!-- Additional payment buttons will be added here -->
                            @else
                                <flux:button wire:click="completeBooking" variant="primary">
                                    Complete Purchase
                                </flux:button>
                            @endif
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

@push('scripts')
    @if (config('cashier.key'))
        <!-- Stripe JS -->
        <script src="https://js.stripe.com/v3/"></script>

        <script>
            let stripe = null;
            let elements = null;
            let paymentElement = null;
            let isLoading = false;

            // Initialize Stripe immediately
            try {
                stripe = Stripe('{{ config('cashier.key') }}');
                console.log('Stripe initialized successfully');
            } catch (error) {
                console.error('Error initializing Stripe:', error);
            }

            // Function to manually initialize Stripe - defined in global scope
            function initializeStripeManually() {
                console.log('Manual Stripe initialization requested');
                if (@json($currentStep) === 3 && @json($paymentMethod) === 'credit_card') {
                    @this.call('createPaymentIntent');
                }
            }

            // Make the function available globally
            window.initializeStripeManually = initializeStripeManually;

            document.addEventListener('livewire:initialized', function() {
                // Check if there's a completed booking to redirect to
                @this.call('checkCompletedBooking', {}, function(response) {
                    if (response && response.redirect) {
                        console.log('Found completed booking, redirecting to:', response.redirect);
                        window.location.href = response.redirect;
                        return;
                    }

                    // Continue with normal initialization if no redirect
                    console.log('No completed booking found, continuing with initialization');
                });

                // Initialize Stripe
                function initStripe() {
                    if (!stripe) {
                        try {
                            const stripeKey = '{{ config('cashier.key') }}';
                            console.log('Initializing Stripe with key:', stripeKey ? 'Key exists' : 'No key found');

                            if (!stripeKey) {
                                console.error('Stripe key is missing. Check your .env file for STRIPE_KEY');
                                document.getElementById('payment-message').textContent =
                                    'Stripe configuration is missing. Please contact support.';
                                document.getElementById('payment-message').classList.remove('hidden');
                                return;
                            }

                            stripe = Stripe(stripeKey);
                            console.log('Stripe initialized successfully');
                        } catch (error) {
                            console.error('Error initializing Stripe:', error);
                            document.getElementById('payment-message').textContent =
                                'Error initializing payment system. Please try again later.';
                            document.getElementById('payment-message').classList.remove('hidden');
                        }
                    }
                }

                // Set up Stripe Elements
                function setupStripeElements(clientSecret) {
                    try {
                        console.log('Setting up Stripe Elements');

                        // Ensure client secret is a string
                        if (typeof clientSecret !== 'string') {
                            console.error('Client secret is not a string:', typeof clientSecret);
                            return;
                        }

                        // Validate client secret format
                        if (!clientSecret || !clientSecret.startsWith('pi_')) {
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

                            // Hide the loading message
                            const loadingMessage = document.getElementById('stripe-loading-message');
                            if (loadingMessage) {
                                loadingMessage.classList.add('hidden');
                            }
                        });

                        paymentElement.on('loaderror', function(event) {
                            console.error('Payment element load error:', event);
                            const messageContainer = document.getElementById('payment-message');
                            if (messageContainer) {
                                messageContainer.textContent = 'Error loading payment form. Please try again.';
                                messageContainer.classList.remove('hidden');
                            }
                        });

                        // Mount the element with a small delay to ensure the DOM is ready
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

                    } catch (error) {
                        console.error('Error setting up Stripe Elements:', error);
                        const messageContainer = document.getElementById('payment-message');
                        if (messageContainer) {
                            messageContainer.textContent = 'Error setting up payment form. Please try again.';
                            messageContainer.classList.remove('hidden');
                        }
                    }
                }

                // Handle form submission
                async function handleSubmit(e) {
                    e.preventDefault();

                    if (!stripe || !elements) {
                        // Stripe.js hasn't loaded yet. Make sure to disable form submission until Stripe.js has loaded.
                        return;
                    }

                    // Set loading state
                    if (isLoading) return;
                    isLoading = true;

                    const submitButton = document.getElementById('submit-payment');
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.innerHTML = 'Processing...';
                    }

                    const messageContainer = document.getElementById('payment-message');
                    if (messageContainer) {
                        messageContainer.classList.add('hidden');
                        messageContainer.textContent = '';
                    }

                    try {
                        // Log the current URL for debugging
                        console.log('Current URL before confirmPayment:', window.location.href);

                        // Create a clean return URL without any query parameters
                        const baseUrl = window.location.origin + window.location.pathname;
                        console.log('Base URL for return_url:', baseUrl);

                        const {
                            error
                        } = await stripe.confirmPayment({
                            elements,
                            confirmParams: {
                                return_url: baseUrl, // Use the clean URL without query parameters
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

                            // Reset loading state
                            isLoading = false;
                            if (submitButton) {
                                submitButton.disabled = false;
                                submitButton.innerHTML = 'Complete Purchase';
                            }
                        } else {
                            // Payment succeeded
                            isLoading = false;

                            // Show a success message
                            const messageContainer = document.getElementById('payment-message');
                            if (messageContainer) {
                                messageContainer.textContent = 'Payment successful! Redirecting to your tickets...';
                                messageContainer.classList.remove('hidden');
                                messageContainer.classList.remove('text-red-500');
                                messageContainer.classList.add('text-green-500');
                            }

                            // Disable the form to prevent multiple submissions
                            const submitButton = document.getElementById('submit-payment');
                            if (submitButton) {
                                submitButton.disabled = true;
                                submitButton.innerHTML = 'Payment Successful!';
                            }

                            // Call the success handler with a slight delay to show the success message
                            setTimeout(() => {
                                // Call the Livewire method to complete the booking
                                // The PHP method now returns a redirect response directly
                                @this.call('handlePaymentSuccess');
                            }, 2000);
                        }
                    } catch (error) {
                        console.error('Error confirming payment:', error);

                        // Show error message
                        if (messageContainer) {
                            messageContainer.textContent = 'An unexpected error occurred. Please try again.';
                            messageContainer.classList.remove('hidden');
                        }

                        // Reset loading state
                        isLoading = false;
                        if (submitButton) {
                            submitButton.disabled = false;
                            submitButton.innerHTML = 'Complete Purchase';
                        }
                    }
                }

                // Listen for Stripe configuration error
                @this.on('stripe-configuration-error', (data) => {
                    console.error('Stripe configuration error:', data.message);
                    const messageContainer = document.getElementById('payment-message');
                    if (messageContainer) {
                        messageContainer.textContent = data.message ||
                            'Error setting up payment system. Please try again later.';
                        messageContainer.classList.remove('hidden');
                    }

                    // Hide the loading message
                    const debugContainer = document.querySelector('#payment-element-container + div');
                    if (debugContainer) {
                        debugContainer.classList.add('hidden');
                    }
                });

                // Listen for redirect-to event
                @this.on('redirect-to', (data) => {
                    console.log('Received redirect-to event with URL:', data.url);
                    if (data.url) {
                        window.location.href = data.url;
                    }
                });

                // Listen for payment intent ready event
                @this.on('payment-intent-ready', () => {
                    console.log('Payment intent ready event received');

                    // Get the client secret from the Livewire component
                    const clientSecret = @this.get('clientSecret');

                    console.log('Client secret type:', typeof clientSecret);

                    if (!clientSecret) {
                        console.error('No client secret available');
                        const messageContainer = document.getElementById('payment-message');
                        if (messageContainer) {
                            messageContainer.textContent = 'Error setting up payment. Please try again.';
                            messageContainer.classList.remove('hidden');
                        }
                        return;
                    }

                    console.log('Client secret received:', typeof clientSecret === 'string' ?
                        clientSecret.substring(0, 10) + '...' : 'Not a string');

                    // Set up Stripe Elements with a small delay to ensure DOM is ready
                    setTimeout(() => {
                        setupStripeElements(clientSecret);
                    }, 500);
                });

                // Add event listener to submit button
                document.addEventListener('click', function(e) {
                    if (e.target && e.target.id === 'submit-payment') {
                        handleSubmit(e);
                    }
                });

                // Initialize payment form when step changes
                @this.on('stepChanged', (step) => {
                    console.log('Step changed to:', step);
                    if (step === 3 && @this.get('paymentMethod') === 'credit_card') {
                        console.log('On payment step with credit card selected, initializing payment form');
                        // This will trigger the createPaymentIntent method on the server
                        @this.call('createPaymentIntent');
                    }
                });

                // Add event listener for retry button
                document.addEventListener('click', function(e) {
                    if (e.target && e.target.id === 'retry-stripe-button') {
                        console.log('Retry button clicked');
                        initializeStripeManually();
                    }
                });

                // Check for payment intent in URL
                document.addEventListener('DOMContentLoaded', function() {
                    // Initialize Stripe
                    initStripe();

                    // Add event listener for retry button (in case it's already in the DOM)
                    const retryButton = document.getElementById('retry-stripe-button');
                    if (retryButton) {
                        retryButton.addEventListener('click', initializeStripeManually);
                    }

                    // Check if we have a payment intent in the URL
                    const urlParams = new URLSearchParams(window.location.search);
                    const paymentIntentClientSecret = urlParams.get('payment_intent_client_secret');

                    if (paymentIntentClientSecret) {
                        // Check the payment status
                        stripe.retrievePaymentIntent(paymentIntentClientSecret).then(function(result) {
                            const paymentIntent = result.paymentIntent;

                            if (paymentIntent.status === 'succeeded') {
                                // Payment succeeded, show success message and notify Livewire
                                const messageContainer = document.getElementById('payment-message');
                                if (messageContainer) {
                                    messageContainer.textContent =
                                        'Payment successful! Redirecting to your tickets...';
                                    messageContainer.classList.remove('hidden');
                                    messageContainer.classList.remove('text-red-500');
                                    messageContainer.classList.add('text-green-500');
                                }

                                // Call the success handler with a slight delay to show the success message
                                setTimeout(() => {
                                    // Call the Livewire method to complete the booking
                                    // The PHP method now returns a redirect response directly
                                    @this.call('handlePaymentSuccess');
                                }, 1500);
                            } else if (paymentIntent.status === 'processing') {
                                // Payment is processing
                                const messageContainer = document.getElementById('payment-message');
                                if (messageContainer) {
                                    messageContainer.textContent = 'Your payment is processing.';
                                    messageContainer.classList.remove('hidden');
                                }
                            } else {
                                // Payment failed
                                const messageContainer = document.getElementById('payment-message');
                                if (messageContainer) {
                                    messageContainer.textContent =
                                        'Your payment was not successful, please try again.';
                                    messageContainer.classList.remove('hidden');
                                }
                            }
                        });
                    }
                });
            });
        </script>
    @endif
@endpush
