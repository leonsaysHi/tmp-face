<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileViewControllerTest extends TestCase
{
    use RefreshDatabase;

     /**
     * @covers \App\Http\Controllers\ProfileViewController::__invoke
     */
    public function testProfile()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->get('/profile')->assertStatus(200);
    }
}
