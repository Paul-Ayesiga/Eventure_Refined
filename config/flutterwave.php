<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Flutterwave Keys
    |--------------------------------------------------------------------------
    |
    | The Flutterwave publishable key and secret key give you access to Flutterwave's
    | API. The "publishable" key is typically used when interacting with
    | Flutterwave.js while the "secret" key accesses private API endpoints.
    |
    */

    'public_key' => env('FLUTTERWAVE_PUBLIC_KEY'),
    'secret_key' => env('FLUTTERWAVE_SECRET_KEY'),
    'encryption_key' => env('FLUTTERWAVE_ENCRYPTION_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Flutterwave Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the environment for Flutterwave. This can be set to
    | "test" or "live". If the environment is set to "test", then all payments
    | will be in test mode.
    |
    */

    'environment' => env('FLUTTERWAVE_ENVIRONMENT', 'test'),

    /*
    |--------------------------------------------------------------------------
    | Currency Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can specify the default currency for Flutterwave payments.
    | You can also specify a display currency that will be shown to users.
    |
    */

    'currency' => env('FLUTTERWAVE_CURRENCY', 'UGX'),
    'display_currency' => env('FLUTTERWAVE_DISPLAY_CURRENCY', 'UGX'),

    /*
    |--------------------------------------------------------------------------
    | Redirect URL
    |--------------------------------------------------------------------------
    |
    | This is the URL that Flutterwave will redirect to after a payment is
    | completed. This URL will receive the payment status and transaction ID.
    |
    */

    'redirect_url' => env('FLUTTERWAVE_REDIRECT_URL', '/payment/callback'),

    /*
    |--------------------------------------------------------------------------
    | Webhook URL
    |--------------------------------------------------------------------------
    |
    | This is the URL that Flutterwave will send webhook notifications to.
    | This URL should be publicly accessible and should handle the webhook
    | notifications from Flutterwave.
    |
    */

    'webhook_url' => env('FLUTTERWAVE_WEBHOOK_URL'),

    /*
    |--------------------------------------------------------------------------
    | Payment Options
    |--------------------------------------------------------------------------
    |
    | Here you can specify the payment options that will be available to users.
    | Options include: card, banktransfer, ussd, credit, barter, payattitude,
    | mobilemoneyghana, mobilemoneyrwanda, mobilemoneyzambia, mobilemoneyuganda,
    | mpesa, qr, etc.
    |
    */

    'payment_options' => env('FLUTTERWAVE_PAYMENT_OPTIONS', 'card, mobilemoneyuganda'),
];
