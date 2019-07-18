<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SessionTimedOutViewControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @covers \App\Http\Controllers\SessionTimedOutViewController::__invoke
     */
    public function testSearch()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->get('/session-timed-out')->assertStatus(200);
    }
}
