<?php

// $midtrans = new Midtrans(); //get model

return [
    'server_key' => env('MIDTRANS_SERVER_KEY', 'Mid-server-hzRwGYjYUgVlCNl-xPyv1PMK'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'Mid-client-AJeb1DtsX5BHmpxH'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
];
