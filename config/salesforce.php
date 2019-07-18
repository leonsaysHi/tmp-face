<?php

return [
    'client_id' => env("SF_CLIENT_ID"),
    'client_secret' => env("SF_CLIENT_SECRET"),
    'username' => env("SF_USERNAME"),
    'password' => env("SF_PASSWORD"),
    'token_uri' => env("SF_TOKEN_URI", "https://test.salesforce.com/services/oauth2/token"),
    'api_uri' => env("SF_API_URI", "https://pfizerbt--Haigang.cs62.my.salesforce.com/services/data/v43.0"),
    'apex_uri' => env("SF_APEX_URI", "https://pfizerbt--Haigang.cs62.my.salesforce.com/services/data/v43.0"),
];
