<?php

namespace App\Providers;

use App\BoxClient;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class BoxProvider extends ServiceProvider
{
    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(boxClient::class, function () {
            $client_id = config("box.client_id");
            $client_secret = config("box.client_secret");
            $redirect_uri = config("box.redirect_uri");
            $enterprise_id = config("box.enterprise_id");
            $app_user_name = config("box.app_user_name");
            $app_user_id = config("box.app_user_id");
            $public_key_id = config("box.public_key_id");
            $passphrase = config("box.passphrase");
            $auth_url = config("box.auth_url");
            $api_url = config("box.api_url");
            $expiration = config("box.expiration");
            $client = app(Client::class);

            return new BoxClient(
                $client_id,
                $client_secret,
                $redirect_uri,
                $enterprise_id,
                $app_user_name,
                $app_user_id,
                $public_key_id,
                $passphrase,
                $auth_url,
                $api_url,
                $expiration,
                $client
            );
        });
    }

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
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['box'];
    }
}
