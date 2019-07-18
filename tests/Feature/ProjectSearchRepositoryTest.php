<?php

namespace Tests\Feature;

use App\ProjectSearchRepository;
use App\SalesForceClient;
use App\Search;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectSearchRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @covers \App\ProjectSearchRepository::saveSearchRecordUpToMaxGivenValue
     * @covers \App\ProjectSearchRepository::saveSearchRecord
     * @covers \App\ProjectSearchRepository::removeLastSearchRecord
     */
    public function testSaveSearchRecordUpToMaxGivenValue()
    {
        $user = factory(\App\User::class)->create();
        Auth::setUser($user);
        $user->id = 1;
        $this->be($user);

        $client = \Mockery::mock(SalesForceClient::class);
        $this->app->instance(SalesForceClient::class, $client);

        $repository = new ProjectSearchRepository($client);

        $search = factory(Search::class)->create();
        $user->searches()->save($search);

        $repository->saveSearchRecordUpToMaxGivenValue($user, ['basicSearchText' => 'foo'], 0);

        $this->assertEquals('foo', $user->searches()->orderBy('id', 'desc')->first()->basic_search_text);
    }
}
