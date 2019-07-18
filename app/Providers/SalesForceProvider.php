<?php

namespace App\Providers;

use App\SalesForceClient;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class SalesForceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SalesForceClient::class, function () {
            $client_id = config("salesforce.client_id");
            $client_secret = config("salesforce.client_secret");
            $username = config("salesforce.username");
            $password = config("salesforce.password");
            $token_uri = config("salesforce.token_uri");
            $api_uri = config("salesforce.api_uri");
            $apex_uri = config("salesforce.apex_uri");
            $client = app(Client::class);

            return new SalesForceClient(
                $client_id,
                $client_secret,
                $username,
                $password,
                $token_uri,
                $api_uri,
                $apex_uri,
                $client
            );
        });
    }
}
