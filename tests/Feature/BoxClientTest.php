<?php

namespace Tests\Feature;

use App\SalesForceClient;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\BoxClient;
use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Cache;
use function GuzzleHttp\json_encode;

class BoxClientTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $user = User::make([
            'name' => 'Test User',
            'guid' => 'xxxx'
        ]);
        Auth::setUser($user);
    }

    /**
     * @covers \App\BoxClient::generateToken
     */
    public function testGenerateToken()
    {
        $mock = new MockHandler([
            new Response(200, [], "{\"access_token\":\"5qGiZNgfxFT5ki1Ie12ot6LN853ClwPB\",\"expires_in\":4062,\"restricted_to\":[],\"token_type\":\"bearer\"}")
        ]);

        $handler = HandlerStack::create($mock);
        $mock = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $mock);

        $client = app(BoxClient::class);
        $client->generateToken();
        $this->assertNotNull(Cache::get('box_access_token'));
    }

    /**
     * @covers \App\BoxClient::getToken
     */
    public function testGetToken()
    {

        $mock = new MockHandler([
            new Response(200, [], "{\"access_token\":\"foo_bar\"}")
        ]);

        $handler = HandlerStack::create($mock);
        $mock = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $mock);
        $client = app(BoxClient::class);
        $client->getToken();
        $this->assertNull(Cache::get('box_access_token'));
        $client->generateToken();
        $client->getToken();
        $this->assertNotNull(Cache::get('box_access_token'));
    }

    /**
     * @covers \App\BoxClient::makeRawGetProjectCall
     */
    public function testMakeRawGetProjectCall()
    {
        $client = \Mockery::mock(SalesForceClient::class);
        $this->app->instance(SalesForceClient::class, $client);
        $client->shouldReceive('generateToken')->andReturn(json_encode(['access_token' => 'foo']));

        $mock = new MockHandler([
            new Response(
                200,
                [],
                json_encode([
                    "totalSize" => 1,
                    "done" => true,
                    "records" => []
                ])
            )
        ]);

        $handler = HandlerStack::create($mock);
        $mock = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $mock);

        $client = app(BoxClient::class);
        $request = $client->makeRawGetProjectCall(1);

        $this->assertEquals($request, [
            "totalSize" => "1",
            "done" => true,
            "records" => []
        ]);
    }
}
