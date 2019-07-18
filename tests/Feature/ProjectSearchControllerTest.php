<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class ProjectSearchControllerTest extends TestCase
{

    /**
     * @covers \App\Http\Controllers\ProjectSearchController::__invoke
     * @covers \App\ProjectSearchRepository::search
     */
    public function testProjectSearch()
    {
        $user = factory(\App\User::class)->create();
        Auth::setUser($user);
        $this->be($user);

        $mock = new MockHandler([
            new Response(200, [], "{\"access_token\":\"00D5C000000LOtI!ARMAQO0V8C30qnwN2J3GPz9IOkdnQcP1ZSXp3abyzpRYJ92ORwLodp5s7D783BIy1IFrzOWSZwo_fRaY538nMc2SP7oO29u_\",\"instance_url\":\"https://pfizerbt--Haigang.cs62.my.salesforce.com\",\"id\":\"https://test.salesforce.com/id/00D5C000000LOtIUAW/0055C000000ooQHQAY\",\"token_type\":\"Bearer\",\"issued_at\":\"1543873716850\",\"signature\":\"A+i8lz+UTUTDwF9lk28LIB11oyPJAcPMVkdl5ANbL7E=\"}"),
            new Response(200, [], "{\"totalProject\":0,\"errorCode\":\"200\"}"),
        ]);
        $handler = HandlerStack::create($mock);
        $mock = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $mock);

        $this->post('/api/search', ['basicSearchText' => 'foo', 'useCached' => false])
            ->assertStatus(200)
            ->assertJsonFragment([
                'totalProject' => 0
            ]);
    }
}
