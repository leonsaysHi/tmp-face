<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchSavedViewControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @covers \App\Http\Controllers\SearchSavedViewController::__invoke
     */
    public function testSavedSearches()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->get('/search/saved')->assertStatus(200);
    }

}