<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class SetUserControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $user = new User();
        $user->name = 'Test User';
        $user->guid = 'xxxx';
        Auth::setUser($user);
    }

    /**
     * @covers \App\Http\Controllers\SetUserController::__invoke
     * @covers \App\UserRepository::set
     */
    public function testSetUser()
    {
        $mock = new MockHandler([
            new Response(200, [], "{\"access_token\":\"00D5C000000LOtI!ARMAQO0V8C30qnwN2J3GPz9IOkdnQcP1ZSXp3abyzpRYJ92ORwLodp5s7D783BIy1IFrzOWSZwo_fRaY538nMc2SP7oO29u_\",\"instance_url\":\"https://pfizerbt--Haigang.cs62.my.salesforce.com\",\"id\":\"https://test.salesforce.com/id/00D5C000000LOtIUAW/0055C000000ooQHQAY\",\"token_type\":\"Bearer\",\"issued_at\":\"1543873716850\",\"signature\":\"A+i8lz+UTUTDwF9lk28LIB11oyPJAcPMVkdl5ANbL7E=\"}"),
            new Response(200, [], "{\"users\":[{\"firstName\":\"foo\",\"lastName\":\"bar\"}],\"errorCode\":\"200\"}"),
        ]);
        $handler = HandlerStack::create($mock);
        $mock = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $mock);

        $this->post('/api/set-user')
            ->assertStatus(200)
            ->assertJsonFragment([
                'users' => [[
                    'firstName' => 'foo',
                    'lastName' => 'bar'
                ]]
            ]);
    }
}
