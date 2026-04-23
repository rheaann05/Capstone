<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PayMongo Secret Key
    |--------------------------------------------------------------------------
    | Your PayMongo secret key. For test mode, use sk_test_*; for live, sk_live_*.
    */
    'secret_key' => env('PAYMONGO_SECRET_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Webhook Secret
    |--------------------------------------------------------------------------
    | Secret used to verify webhook signatures.
    */
    'webhook_secret' => env('PAYMONGO_WEBHOOK_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Test Mode
    |--------------------------------------------------------------------------
    | Set to true for PayMongo test environment.
    */
    'test_mode' => env('PAYMONGO_TEST_MODE', true),
];