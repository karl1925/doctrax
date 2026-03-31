<?php

return [

    'internal_routing' => env('INTERNAL_ROUTING_MODULE', false),
    'send_credentials' => env('SEND_CREDENTIALS_UPON_CREATE', false),
    'allow_new_govmail' => env('ALLOW_NEW_GOVMAIL', false),
    'test_mode' => env('TEST_MODE', true),
];