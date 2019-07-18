<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetEnvControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @covers App\Http\Controllers\GetEnvController::__invoke
     */
    public function testGetEnv()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->get('/api/get-env')->assertStatus(200)->assertSeeText(env('APP_ENV'));
    }
}
