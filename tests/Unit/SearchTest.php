<?php

namespace Tests\Unit;

use App\Search;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @covers App\Search::user
     */
    public function testUser()
    {
        $mock = new Search();
        $this->app->instance(Search::class, $mock);

        $search = factory(Search::class)->create();
        $user = factory(User::class)->create();
        $search->user()->associate($user);
        $user->save();

        $this->assertNotEmpty($search->user()->get());
        $this->assertNotNull($search->basic_search_text);
    }
}
