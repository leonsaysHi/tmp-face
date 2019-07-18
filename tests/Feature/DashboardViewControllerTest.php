<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardViewControllerTest extends TestCase
{

    use RefreshDatabase;

     /**
     * @covers App\Http\Controllers\DashboardViewController::__invoke
     */
    public function testDashboard()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->get('/dashboard')->assertStatus(200);
    }
}
