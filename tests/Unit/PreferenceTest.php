<?php

namespace Tests\Unit;

use App\Preference;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PreferenceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @covers App\Preference::user
     */
    public function testUserPreference()
    {
        $mock = new Preference();
        $this->app->instance(Preference::class, $mock);

        $preference = factory(Preference::class)->create();
        $user = factory(User::class)->create();
        $preference->user()->attach($user->first()->id);

        $this->assertNotEmpty($preference->user()->get());
        $this->assertNotNull($preference->value);
    }
}
