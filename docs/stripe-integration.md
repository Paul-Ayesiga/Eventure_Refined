# Stripe Payment Integration Guide

This document outlines how Stripe payment processing was integrated into the EventureRefined application for handling event ticket bookings.

## Overview

The integration allows users to process real payments for event ticket bookings using Stripe. The implementation follows these key steps:

1. Creating a payment intent when a user initiates payment
2. Displaying a secure payment form using Stripe Elements
3. Processing the payment and creating the booking upon successful payment

## Prerequisites

- Laravel Cashier package installed (`composer require laravel/cashier`)
- Stripe account with API keys
- Environment variables configured in `.env` file

## Environment Configuration

Add the following variables to your `.env` file:

```
STRIPE_KEY=pk_test_your_publishable_key
STRIPE_SECRET=sk_test_your_secret_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret
CASHIER_CURRENCY=usd
CASHIER_PAYMENT_NOTIFICATION=true
```

## Implementation Details

### 1. Backend Implementation (PHP)

#### Payment Intent Creation

The payment process begins with creating a payment intent on the server side:

```php
/**
 * Initialize the payment process
 */
public function initializePayment()
{
    // Validate form data
    $this->validate([
        'selectedCustomerId' => 'required|exists:users,id',
        'selectedTicketId' => 'required|exists:tickets,id',
        'ticketQuantity' => 'required|integer|min:1'
    ]);
    
    // Get customer and ticket
    $customer = \App\Models\User::find($this->selectedCustomerId);
    $ticket = Ticket::find($this->selectedTicketId);
    $totalAmount = $ticket->price * $this->ticketQuantity;
    
    // Create a payment intent
    $this->createPaymentIntent($customer, $totalAmount);
}

/**
 * Create a Stripe payment intent for the booking
 */
public function createPaymentIntent($customer, $amount)
{
    try {
        // Get or create a Stripe customer
        if (!$customer->stripe_id) {
            $customer->createAsStripeCustomer([
                'email' => $customer->email,
                'name' => $customer->name
            ]);
        }
        
        // Make sure we have a valid currency
        $currency = strtolower($this->event->currency ?: config('cashier.currency', 'usd'));
        
        // Create the payment intent
        $stripe = new StripeClient(config('cashier.secret'));
        $paymentIntent = $stripe->paymentIntents->create([
            'amount' => (int)($amount * 100), // Convert to cents
            'currency' => $currency,
            'customer' => $customer->stripe_id,
            'metadata' => [
                'event_id' => $this->eventId,
                'ticket_id' => $this->selectedTicketId,
                'quantity' => $this->ticketQuantity,
            ],
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);
        
        // Store the payment intent details
        $this->paymentIntentId = $paymentIntent->id;
        $this->clientSecret = $paymentIntent->client_secret;
        
        // Notify the user and dispatch event to frontend
        $this->dispatch('toast', "Payment intent created. Please complete the payment.", 'info', 'top-right');
        $this->dispatch('payment-intent-created', clientSecret: $this->clientSecret);
        
    } catch (\Exception $e) {
        Log::error('Error creating payment intent: ' . $e->getMessage());
        $this->dispatch('toast', "Error creating payment: {$e->getMessage()}", 'error', 'top-right');
    }
}
```

#### Payment Status Checking

```php
/**
 * Check the status of a payment intent
 */
public function checkPaymentStatus()
{
    if (!$this->paymentIntentId) {
        return 'requires_payment_method';
    }
    
    try {
        $stripe = new StripeClient(config('cashier.secret'));
        $paymentIntent = $stripe->paymentIntents->retrieve($this->paymentIntentId);
        $this->paymentStatus = $paymentIntent->status;
        
        return $paymentIntent->status;
    } catch (\Exception $e) {
        Log::error('Error checking payment status: ' . $e->getMessage());
        return 'error';
    }
}
```

#### Handling Successful Payment

```php
/**
 * Handle successful payment
 */
public function handlePaymentSuccess()
{
    $this->paymentStatus = 'succeeded';
    $this->dispatch('toast', "Payment successful! Creating booking...", 'success', 'top-right');
    $this->simulatePurchase(); // Continue with booking creation
}
```

### 2. Frontend Implementation (JavaScript)

#### Stripe Elements Integration

```javascript
document.addEventListener('livewire:initialized', () => {
    // Initialize Stripe
    try {
        stripe = Stripe('{{ config('cashier.key') }}');
    } catch (error) {
        console.error('Error initializing Stripe:', error);
    }

    // Listen for payment intent created event
    @this.on('payment-intent-created', (data) => {
        const clientSecret = data.clientSecret;
        
        // Wait a moment for the DOM to update before setting up elements
        setTimeout(() => {
            setupStripeElements(clientSecret);
        }, 500);
    });
    
    // Store the client secret when it's created
    let lastClientSecret = null;
    @this.on('payment-intent-created', (data) => {
        lastClientSecret = data.clientSecret;
    });
    
    // Also listen for Livewire updates that might affect the payment form
    document.addEventListener('livewire:update', () => {
        const paymentContainer = document.getElementById('payment-element');
        if (paymentContainer && lastClientSecret && !paymentElement) {
            setupStripeElements(lastClientSecret);
        }
    });
});

function setupStripeElements(clientSecret) {
    try {
        // Validate client secret
        if (!clientSecret || typeof clientSecret !== 'string' || !clientSecret.startsWith('pi_')) {
            console.error('Invalid client secret format');
            return;
        }
        
        // Clear any existing elements
        const paymentContainer = document.getElementById('payment-element');
        if (paymentContainer) {
            paymentContainer.innerHTML = '';
        } else {
            console.error('Payment container not found!');
            return;
        }
        
        // Create elements instance
        elements = stripe.elements({
            clientSecret: clientSecret,
            appearance: {
                theme: document.documentElement.classList.contains('dark') ? 'night' : 'stripe',
                variables: {
                    colorPrimary: '#10b981', // teal-500
                }
            }
        });
        
        // Create and mount the Payment Element
        paymentElement = elements.create('payment');
        paymentElement.mount('#payment-element');
        
        // Handle form submission
        const submitButton = document.getElementById('submit-payment');
        if (submitButton) {
            submitButton.addEventListener('click', handleSubmit);
        } else {
            console.error('Submit button not found!');
        }
    } catch (error) {
        console.error('Error setting up Stripe Elements:', error);
    }
}

async function handleSubmit(e) {
    e.preventDefault();
    setLoading(true);
    
    const messageContainer = document.getElementById('payment-message');
    if (messageContainer) {
        messageContainer.classList.add('hidden');
        messageContainer.textContent = '';
    }
    
    try {
        const { error } = await stripe.confirmPayment({
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
        }
        setLoading(false);
    }
}

function setLoading(isLoading) {
    const submitButton = document.getElementById('submit-payment');
    const spinner = document.getElementById('spinner');
    const buttonText = document.getElementById('button-text');
    
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
```

### 3. Blade Template Integration

#### Payment Form

```html
<!-- Stripe Payment Form (shown when useStripePayment is true) -->
@if ($useStripePayment)
    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
        <div class="flex justify-between items-center mb-3">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Payment Information</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400">Secure payment via Stripe</p>
        </div>
        
        @if ($clientSecret)
            <div id="payment-element" class="mb-4"></div>
            
            <div id="payment-message" class="text-sm text-red-500 mb-4 hidden"></div>
            
            <div class="flex justify-between">
                <flux:button variant="ghost" wire:click="cancelPayment">Cancel Payment</flux:button>
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
@endif
```

#### Form Submission

```html
<form wire:submit.prevent="{{ $useStripePayment && !$clientSecret ? 'initializePayment' : 'simulatePurchase' }}">
    <!-- Form fields here -->
    
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
</form>
```

## Payment Flow

1. **User fills out booking form**:
   - Selects customer, ticket, and quantity
   - Toggles "Use Stripe Payment" to ON

2. **User initializes payment**:
   - Clicks "Initialize Payment" button
   - Backend creates a payment intent
   - Frontend receives client secret and displays payment form

3. **User completes payment**:
   - Enters card details in Stripe Elements form
   - Clicks "Pay Now" button
   - Stripe processes the payment

4. **Booking creation**:
   - On successful payment, `handlePaymentSuccess()` is called
   - Backend creates the booking with payment status "paid"
   - User receives confirmation

## Testing

Use these test card numbers for testing different scenarios:

- **Successful payment**: 4242 4242 4242 4242
- **Authentication required**: 4000 0025 0000 3155
- **Payment declined**: 4000 0000 0000 9995

For any test card, you can use:
- Any future expiration date (MM/YY)
- Any 3-digit CVC
- Any postal code

## Important Notes

1. **Client Secret Handling**:
   - The client secret must be passed as a named parameter in Livewire events to prevent it from being wrapped in an array
   - Example: `$this->dispatch('payment-intent-created', clientSecret: $this->clientSecret);`

2. **DOM Updates**:
   - Use a short delay before initializing Stripe Elements to ensure the DOM has updated
   - Listen for Livewire updates to handle cases where the payment container appears after an update

3. **Error Handling**:
   - Validate the client secret format before using it
   - Provide clear error messages to users
   - Log errors for debugging

4. **Security Considerations**:
   - Never log the full client secret
   - Use HTTPS in production
   - Follow Stripe's security best practices

## Production Considerations

1. **Switch to Live Keys**:
   - Replace test keys with live keys when going to production
   - Update the `.env` file with live keys (starting with `pk_live_` and `sk_live_`)

2. **Set Up Webhooks**:
   - Configure webhooks to handle asynchronous events
   - Implement webhook endpoints for events like `payment_intent.succeeded` and `payment_intent.payment_failed`

3. **Error Monitoring**:
   - Set up proper error monitoring and alerting
   - Regularly check Stripe Dashboard for payment issues
