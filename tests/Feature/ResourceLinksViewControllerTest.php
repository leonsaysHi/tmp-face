<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResourceLinksViewControllerTest extends TestCase
{
    use RefreshDatabase;

     /**
     * @covers \App\Http\Controllers\ResourceLinksViewController::__invoke
     */
    public function testResourceLinks()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->get('/resource-links')->assertStatus(200);
    }
}
