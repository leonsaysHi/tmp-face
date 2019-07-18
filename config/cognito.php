<?php
use App\CognitoUserService;

return [
  'service_class'     => env('COGNITO_SERVICE_CLASS', CognitoUserService::class),
  'controller_class'  => env('COGNITO_CONTROLLER_CLASS', 'App\Http\Controllers\CognitoAuthOverrideController@login'),
  'client_id'     => env('COGNITO_KEY'),
  'client_secret' => env('COGNITO_SECRET'),
  'endpoint'      => env('COGNITO_ENDPOINT'),
  'redirect_uri'  => env('COGNITO_REDIRECT_URI', env('APP_URL') . '/auth/cognito'),
  'grant_type'    => env('COGNITO_GRANT_TYPE', 'authorization_code'),
  'keyfile'       => env('COGNITO_KEYFILE', base_path('storage/app/jwks.json')),
  'user_model'    => \App\User::class /* Map to user model */
];
