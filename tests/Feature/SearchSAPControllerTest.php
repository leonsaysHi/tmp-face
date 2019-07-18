<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class SearchSAPControllerTest extends TestCase
{
    /**
     * @covers \App\Http\Controllers\SearchSAPController::__invoke
     * @covers \App\SAPRepository::search
     */
    public function testSearchSap()
    {
        $user = factory(\App\User::class)->create();
        Auth::setUser($user);
        $this->be($user);

        $mock = new MockHandler([
            new Response(200, [], "{\"access_token\":\"fsXvbrrtsRUUZ2JYvrkx1PRGpcvNJPXy6T7G63g8CIAsiV7mwY2CDTOAxZ08\",\"instance_url\":\"https://pfizerbt--Haigang.cs62.my.salesforce.com\",\"id\":\"https://test.salesforce.com/id/00D5C000000LOtIUAW/0055C000000ooQHQAY\",\"token_type\":\"Bearer\",\"issued_at\":\"1543873716850\",\"signature\":\"A+i8lz+UTUTDwF9lk28LIB11oyPJAcPMVkdl5ANbL7E=\"}"),
            new Response(200, [], "{\"saps\":[{\"sfdcId\":\"foo\", \"name\":\"bar\"}],\"errorCode\":\"200\"}"),
        ]);

        $handler = HandlerStack::create($mock);
        $mock = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $mock);

        $payload = ['searchText' => 'bar'];

        $this->post('/api/search-sap', $payload)
            ->assertStatus(200)
            ->assertJsonFragment([
                'saps' => [[
                    'sfdcId' => 'foo',
                    'name' => 'bar'
                ]]
            ]);
    }
}
