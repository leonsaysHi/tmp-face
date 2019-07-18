<?php

namespace Tests\Feature;

use App\GetLovRepository;
use App\SalesForceClient;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class GetLovRepositoryTest extends TestCase
{
    /**
     * @covers App\GetLovRepository::checkIfItsAParent
     * @covers App\GetLovRepository::mapListOfValues
     */
    public function testMapListOfValues()
    {
        $user = factory(\App\User::class)->create();
        Auth::setUser($user);
        $this->be($user);

        $mockData = "{\"lovs\":[{\"tmsId\":\"266140\",\"sfdcId\":\"a4a5C000000L5hHQAS\",\"parentID\":null,\"overrideName\":null,\"name\":\"Viracept (nelfinavir)\",\"myworkId\":null,\"lovType\":\"Brand\",\"itemOrder\":null,\"isActive\":true,\"displayName\":\"Viracept (nelfinavir)\",\"cepId\":null},{\"tmsId\":\"266184\",\"sfdcId\":\"a4a5C000000L5hJQAS\",\"parentID\":null,\"overrideName\":null,\"name\":\"Vizimpro (dacomitinib)\",\"myworkId\":null,\"lovType\":\"Brand\",\"itemOrder\":null,\"isActive\":true,\"displayName\":\"Vizimpro (dacomitinib)\",\"cepId\":null},{\"tmsId\":\"264032\",\"sfdcId\":\"a4a5C000000L5ibQAC\",\"parentID\":null,\"overrideName\":null,\"name\":\"Rest of Eastern Europe\",\"myworkId\":\"C12\",\"lovType\":\"Country\",\"itemOrder\":null,\"isActive\":true,\"displayName\":\"Rest of Eastern Europe\",\"cepId\":null},{\"tmsId\":\"274000\",\"sfdcId\":\"a4a5C000000L5j5QAC\",\"parentID\":null,\"overrideName\":null,\"name\":\"Asia/Pacific\",\"myworkId\":\"R1\",\"lovType\":\"Country\",\"itemOrder\":null,\"isActive\":true,\"displayName\":\"Asia/Pacific\",\"cepId\":null},{\"tmsId\":\"264014\",\"sfdcId\":\"a4a5C000000L5iKQAS\",\"parentID\":\"R1\",\"overrideName\":null,\"name\":\"Japan\",\"myworkId\":\"C15\",\"lovType\":\"Country\",\"itemOrder\":null,\"isActive\":true,\"displayName\":\"Japan\",\"cepId\":null},{\"tmsId\":\"264015\",\"sfdcId\":\"a4a5C000000L5iLQAS\",\"parentID\":\"R1\",\"overrideName\":null,\"name\":\"Korea\",\"myworkId\":\"C16\",\"lovType\":\"Country\",\"itemOrder\":null,\"isActive\":true,\"displayName\":\"Korea\",\"cepId\":null}],\"errorCode\":\"200\"}";
        $mockData = json_decode($mockData, true);

        $expected = [
            "Brand" => [
                [
                    "tmsId" => "266140",
                    "sfdcId" => "a4a5C000000L5hHQAS",
                    "parentID" => null,
                    "overrideName" => null,
                    "name" => "Viracept (nelfinavir)",
                    "myworkId" => null,
                    "lovType" => "Brand",
                    "itemOrder" => null,
                    "isActive" => true,
                    "displayName" => "Viracept (nelfinavir)",
                    "cepId" => null
                ], [
                    "tmsId" => "266184",
                    "sfdcId" => "a4a5C000000L5hJQAS",
                    "parentID" => null,
                    "overrideName" => null,
                    "name" => "Vizimpro (dacomitinib)",
                    "myworkId" => null,
                    "lovType" => "Brand",
                    "itemOrder" => null,
                    "isActive" => true,
                    "displayName" => "Vizimpro (dacomitinib)",
                    "cepId" => null
                ]
            ],
            "Country" => [
                [
                    "tmsId" => "264032",
                    "sfdcId" => "a4a5C000000L5ibQAC",
                    "parentID" => null,
                    "overrideName" => null,
                    "name" => "Rest of Eastern Europe",
                    "myworkId" => "C12",
                    "lovType" => "Country",
                    "itemOrder" => null,
                    "isActive" => true,
                    "displayName" => "Rest of Eastern Europe",
                    "cepId" => null,
                    "children" => []
                ],
                [
                    "tmsId" => "274000",
                    "sfdcId" => "a4a5C000000L5j5QAC",
                    "parentID" => null,
                    "overrideName" => null,
                    "name" => "Asia/Pacific",
                    "myworkId" => "R1",
                    "lovType" => "Country",
                    "itemOrder" => null,
                    "isActive" => true,
                    "displayName" => "Asia/Pacific",
                    "cepId" => null,
                    "children" => [
                        [
                            "tmsId" => "264014",
                            "sfdcId" => "a4a5C000000L5iKQAS",
                            "parentID" => "R1",
                            "overrideName" => null,
                            "name" => "Japan",
                            "myworkId" => "C15",
                            "lovType" => "Country",
                            "itemOrder" => null,
                            "isActive" => true,
                            "displayName" => "Japan",
                            "cepId" => null
                        ],
                        [
                            "tmsId" => "264015",
                            "sfdcId" => "a4a5C000000L5iLQAS",
                            "parentID" => "R1",
                            "overrideName" => null,
                            "name" => "Korea",
                            "myworkId" => "C16",
                            "lovType" => "Country",
                            "itemOrder" => null,
                            "isActive" => true,
                            "displayName" => "Korea",
                            "cepId" => null
                        ]
                    ]
                ]
            ]
        ];

        $client = \Mockery::mock(SalesForceClient::class);
        $this->app->instance(SalesForceClient::class, $client);

        $repo = new GetLovRepository($client);
        $response = $repo->mapListOfValues($mockData);
        $emptyResponse = $repo->mapListOfValues([]);
        sort($response['Country'][1]['children']);

        $this->assertEquals($response, $expected);
        $this->assertEquals($emptyResponse, []);
    }

    /**
     * @covers \App\GetLovRepository::getLov
     */
    public function testGetLov()
    {
        $user = factory(\App\User::class)->create();
        Auth::setUser($user);
        $this->be($user);

        $mock = new MockHandler([
            new Response(200, [], "{\"access_token\":\"00D5C000000LOtI!ARMAQO0V8C30qnwN2J3GPz9IOkdnQcP1ZSXp3abyzpRYJ92ORwLodp5s7D783BIy1IFrzOWSZwo_fRaY538nMc2SP7oO29u_\",\"instance_url\":\"https://pfizerbt--Haigang.cs62.my.salesforce.com\",\"id\":\"https://test.salesforce.com/id/00D5C000000LOtIUAW/0055C000000ooQHQAY\",\"token_type\":\"Bearer\",\"issued_at\":\"1543873716850\",\"signature\":\"A+i8lz+UTUTDwF9lk28LIB11oyPJAcPMVkdl5ANbL7E=\"}"),
            new Response(200, [], "{\"data\":[],\"errorCode\":\"200\"}"),
        ]);
        $handler = HandlerStack::create($mock);
        $mock = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $mock);

        $this->post('/api/get-lov')
            ->assertStatus(200);
    }
}
