<?php

return [
    'password_access_client'=>[
        'client_name' => env('OAUTH_CLIENT_NAME'),
        'client_id' => env('OAUTH_CLIENT_ID'),
        'client_secret' => env('OAUTH_CLIENT_SECRET'),
    ],
    'personal_grant_client'=>[
        'client_name' => env('OAUTH2_CLIENT_NAME'),
        'client_id' => env('OAUTH2_CLIENT_ID'),
        'client_secret' => env('OAUTH2_CLIENT_SECRET'),
    ]
];
