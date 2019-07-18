<?php

namespace Tests\Unit;

use App\Preference;
use App\Search;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testApiToken()
    {
        $user = factory(\App\User::class)->create();
        $this->assertNotNull($user->api_token);
    }

    /**
     * @covers App\User::preferences
     */
    public function testUserPreference()
    {
        $preference = factory(Preference::class)->create();
        $user = factory(User::class)->create();
        $user->preferences()->attach($preference->first()->id);

        $this->assertNotEmpty($user->preferences()->get());
        $this->assertNotNull($preference->value);
    }

    /**
     * @covers App\User::searches
     */
    public function testSearches()
    {
        $search = factory(Search::class)->create();
        $user = factory(User::class)->create();
        $user->searches()->save($search);

        $this->assertNotEmpty($user->searches()->get());
        $this->assertNotNull($search->basic_search_text);
    }
}
