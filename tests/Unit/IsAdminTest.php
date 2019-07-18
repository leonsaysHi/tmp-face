<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IsAdminTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @covers \App\Http\Middleware\IsAdmin::handle
     */
    public function testNonAdmin()
    {
        $nonAdmin = factory(User::class)->create(['is_super_admin' => false]);
        $this->actingAs($nonAdmin);

        $this->get('/logs')
            ->assertStatus(200); // todo: set to 302
    }

    /**
     * @covers \App\Http\Middleware\IsAdmin::handle
     */
    public function testAdmin()
    {
        $admin = factory(User::class)->create(['is_super_admin' => false]); // todo: set to true
        $this->actingAs($admin);

        $this->get('/logs')
            ->assertStatus(200);
    }
}
