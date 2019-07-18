<?php

namespace Tests\Feature;

use App\ProjectSearchRepository;
use App\SalesForceClient;
use App\Search;
use App\SearchRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @covers \App\SearchRepository::__construct
     * @covers \App\SearchRepository::historyIndex
     * @covers \App\SearchRepository::show
     */
    public function testHistoryIndex()
    {
        $user = factory(\App\User::class)->create();
        Auth::setUser($user);
        $this->be($user);

        $mockClient = \Mockery::mock(SalesForceClient::class);
        App::instance(SalesForceClient::class, $mockClient);

        $repository = new ProjectSearchRepository($mockClient);
        $mock = new SearchRepository($repository);

        $mockData = $mock->historyIndex(0, $user)->toArray();

        $this->assertEquals($mockData, []);
    }

    /**
     * @covers \App\SearchRepository::__construct
     * @covers \App\SearchRepository::savedStore
     */
    public function testSavedStore()
    {
        $user = factory(\App\User::class)->create();
        Auth::setUser($user);
        $this->be($user);

        $mockClient = \Mockery::mock(SalesForceClient::class);
        App::instance(SalesForceClient::class, $mockClient);

        $repository = new ProjectSearchRepository($mockClient);
        $mock = new SearchRepository($repository);

        $request = [
            'searchText' => 'foo',
            'filters' => [],
            'is_saved', true
        ];

        $mockData = $mock->savedStore($request, 1)->toArray();

        $this->assertArrayHasKey("basic_search_text", $mockData);
    }


    /**
     * @covers \App\SearchRepository::__construct
     * @covers \App\SearchRepository::savedIndex
     */
    public function testSavedIndex()
    {
        $user = factory(\App\User::class)->create();
        Auth::setUser($user);
        $this->be($user);

        $mockClient = \Mockery::mock(SalesForceClient::class);
        App::instance(SalesForceClient::class, $mockClient);

        $repository = new ProjectSearchRepository($mockClient);
        $mock = new SearchRepository($repository);


        $mockData = $mock->savedIndex($user)->toArray();

        $this->assertEquals($mockData, []);
    }

    /**
     * @covers \App\SearchRepository::__construct
     * @covers \App\SearchRepository::savedDestroy
     */
    public function testSavedDestroy()
    {
        $user = factory(\App\User::class)->create();
        Auth::setUser($user);
        $this->be($user);

        $record = factory(Search::class)->create();
        $record->save();

        $mockClient = \Mockery::mock(SalesForceClient::class);
        App::instance(SalesForceClient::class, $mockClient);

        $repository = new ProjectSearchRepository($mockClient);
        $mock = new SearchRepository($repository);

        $mockData = $mock->savedDestroy($record->id);
        $this
            ->assertEquals(
                json_decode($mockData->getContent(), true),
                ["message" =>"Search record successfully removed."]
            );
    }
}
