<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ForgotPasswordControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @covers App\Http\Controllers\Auth\ForgotPasswordController::sendResetLinkEmail
     */
    public function testRedirectOnRegister()
    {
        $this->call("POST", "/password/email")->assertStatus(302);
    }

    /**
     * @covers App\Http\Controllers\Auth\ForgotPasswordController::showLinkRequestForm
     */
    public function testShowForm()
    {
        $this->markTestSkipped("@TODO Travis not letting me get into debug :(");
        $this->call("GET", "/password/reset")->assertStatus(302);
    }
}
