<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuración de Pagos
    |--------------------------------------------------------------------------
    |
    | Configuración para los diferentes métodos de pago soportados:
    | - PayPal
    | - Stripe
    |
    */

    'paypal' => [
        'mode' => env('PAYPAL_MODE', 'sandbox'), // sandbox o live
        'sandbox' => [
            'client_id' => env('PAYPAL_SANDBOX_CLIENT_ID', ''),
            'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET', ''),
        ],
        'live' => [
            'client_id' => env('PAYPAL_LIVE_CLIENT_ID', ''),
            'client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET', ''),
        ],
        'currency' => env('PAYPAL_CURRENCY', 'USD'),
    ],

    'stripe' => [
        'publishable_key' => env('STRIPE_PUBLISHABLE_KEY', ''),
        'secret_key' => env('STRIPE_SECRET_KEY', ''),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET', ''),
        'currency' => env('STRIPE_CURRENCY', 'usd'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Métodos de Pago Habilitados
    |--------------------------------------------------------------------------
    |
    | Define qué métodos de pago están habilitados en el sistema
    |
    */

    'metodos_habilitados' => [
        'paypal' => env('PAYMENT_PAYPAL_ENABLED', true),
        'stripe' => env('PAYMENT_STRIPE_ENABLED', true),
        'tarjeta' => env('PAYMENT_CARD_ENABLED', true),
        'efectivo' => env('PAYMENT_CASH_ENABLED', true),
        'transferencia' => env('PAYMENT_TRANSFER_ENABLED', true),
    ],

];
