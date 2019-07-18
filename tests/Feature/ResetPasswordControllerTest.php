<?php

namespace Tests\Feature;

use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @covers App\Http\Controllers\Auth\ResetPasswordController::reset
     */
    public function testRedirectPost()
    {
        $this->post("/password/reset")->assertStatus(302);
    }


    /**
     * @covers App\Http\Controllers\Auth\ResetPasswordController::showResetForm
    */
    public function testRedirectGetWithToken()
    {
        $user = factory(\App\User::class)->create();
        $token = Str::random();
        DB::table("password_resets")->insert(
            ['email' => $user->email,
            'token' => $token]
        );
        $this->get("/password/reset/{$token}")->assertStatus(302);
    }
}
