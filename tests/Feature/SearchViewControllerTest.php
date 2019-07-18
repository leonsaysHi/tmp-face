<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchViewControllerTest extends TestCase
{
    use RefreshDatabase;

     /**
     * @covers \App\Http\Controllers\SearchViewController::__invoke
     */
    public function testSearch()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->get('/search')->assertStatus(200);
    }
}
