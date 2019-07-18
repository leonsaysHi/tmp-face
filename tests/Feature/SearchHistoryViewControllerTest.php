<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchHistoryViewControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @covers \App\Http\Controllers\SearchHistoryViewController::__invoke
     */
    public function testSearchHistory()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->get('/search/history')->assertStatus(200);
    }
}