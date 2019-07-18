<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BaseInstallTest extends TestCase
{

    public function testRedirectOnRegister()
    {
        $this->call("GET", "/register")->assertStatus(302);
        $this->call("POST", "/register")->assertStatus(302);
    }

    public function testApiAndToken()
    {
        $user = factory(\App\User::class)->create();
        $token = $user->api_token;
        $this->json("GET", "/api/user?api_token=$token")->assertStatus(200);
    }
}
