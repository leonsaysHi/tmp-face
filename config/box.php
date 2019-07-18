<?php

return [
    'client_id' => env('BOX_CLIENT_ID'),
    'client_secret' => env('BOX_SECRET'),
    'redirect_uri' => env('BOX_REDIRECT_URI'),
    'enterprise_id' => env('BOX_ENTERPRISE_ID'),
    'app_user_name' => env('BOX_APP_USER_NAME'),
    'app_user_id' => env('BOX_APP_USER_ID'),
    'public_key_id' => env('BOX_PUBLIC_KEY_ID'),
    'passphrase' => env('BOX_PASSPHRASE'),
    'auth_url' => env('BOX_AUTH_URL'),
    'api_url' => env('BOX_API_URL'),
    'folder_id' => env('BOX_FOLDER_ID'),
    'support_email' => env('BOX_SUPPORT_EMAIL'),
    'as_user_id' => env('BOX_AS_USER_ID'),
    'expiration' => time() + 45,
];
