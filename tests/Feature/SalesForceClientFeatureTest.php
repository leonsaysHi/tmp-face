<?php

namespace Tests\Feature;

use App\SalesForceClient;
use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SalesForceClientFeatureTest extends TestCase
{
    private $query = 'SELECT name, LastModifiedDate from Meeting__c limit 10';

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
     * @covers \App\SalesForceClient::generateToken
     */
    public function testGenerateToken()
    {
        $mock = new MockHandler([
            new Response(200, [], "{\"access_token\":\"00D5C000000LOtI!ARMAQO0V8C30qnwN2J3GPz9IOkdnQcP1ZSXp3abyzpRYJ92ORwLodp5s7D783BIy1IFrzOWSZwo_fRaY538nMc2SP7oO29u_\",\"instance_url\":\"https://pfizerbt--Haigang.cs62.my.salesforce.com\",\"id\":\"https://test.salesforce.com/id/00D5C000000LOtIUAW/0055C000000ooQHQAY\",\"token_type\":\"Bearer\",\"issued_at\":\"1543873716850\",\"signature\":\"A+i8lz+UTUTDwF9lk28LIB11oyPJAcPMVkdl5ANbL7E=\"}")
        ]);
        $handler = HandlerStack::create($mock);
        $mock = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $mock);

        $client = app(SalesForceClient::class);
        $client->generateToken();
        $this->assertEquals('00D5C000000LOtI!ARMAQO0V8C30qnwN2J3GPz9IOkdnQcP1ZSXp3abyzpRYJ92ORwLodp5s7D783BIy1IFrzOWSZwo_fRaY538nMc2SP7oO29u_', Cache::get('access_token'));
    }

    /**
     * @covers \App\SalesForceClient::query
     */
    public function testAccessApi()
    {
        $mock = new MockHandler([
            new Response(200, [], "{\"access_token\":\"00D5C000000LOtI!ARMAQO0V8C30qnwN2J3GPz9IOkdnQcP1ZSXp3abyzpRYJ92ORwLodp5s7D783BIy1IFrzOWSZwo_fRaY538nMc2SP7oO29u_\",\"instance_url\":\"https://pfizerbt--Haigang.cs62.my.salesforce.com\",\"id\":\"https://test.salesforce.com/id/00D5C000000LOtIUAW/0055C000000ooQHQAY\",\"token_type\":\"Bearer\",\"issued_at\":\"1543873716850\",\"signature\":\"A+i8lz+UTUTDwF9lk28LIB11oyPJAcPMVkdl5ANbL7E=\"}"),
            new Response(200, [], "{\"totalSize\":10,\"done\":true,\"records\":[{\"attributes\":{\"type\":\"Meeting__c\",\"url\":\"/services/data/v43.0/sobjects/Meeting__c/a495C0000004KTQQA2\"},\"Name\":\"Meeting-000110\",\"LastModifiedDate\":\"2018-11-30T16:14:15.000+0000\"},{\"attributes\":{\"type\":\"Meeting__c\",\"url\":\"/services/data/v43.0/sobjects/Meeting__c/a495C0000004KTRQA2\"},\"Name\":\"Meeting-000111\",\"LastModifiedDate\":\"2018-11-30T16:14:15.000+0000\"},{\"attributes\":{\"type\":\"Meeting__c\",\"url\":\"/services/data/v43.0/sobjects/Meeting__c/a495C0000004KTSQA2\"},\"Name\":\"Meeting-000112\",\"LastModifiedDate\":\"2018-11-30T16:14:15.000+0000\"},{\"attributes\":{\"type\":\"Meeting__c\",\"url\":\"/services/data/v43.0/sobjects/Meeting__c/a495C0000004KTTQA2\"},\"Name\":\"Meeting-000113\",\"LastModifiedDate\":\"2018-11-30T16:14:15.000+0000\"},{\"attributes\":{\"type\":\"Meeting__c\",\"url\":\"/services/data/v43.0/sobjects/Meeting__c/a495C0000004KTUQA2\"},\"Name\":\"Meeting-000114\",\"LastModifiedDate\":\"2018-11-30T16:14:15.000+0000\"},{\"attributes\":{\"type\":\"Meeting__c\",\"url\":\"/services/data/v43.0/sobjects/Meeting__c/a495C0000004KTVQA2\"},\"Name\":\"Meeting-000115\",\"LastModifiedDate\":\"2018-11-30T16:14:15.000+0000\"},{\"attributes\":{\"type\":\"Meeting__c\",\"url\":\"/services/data/v43.0/sobjects/Meeting__c/a495C0000004KTWQA2\"},\"Name\":\"Meeting-000116\",\"LastModifiedDate\":\"2018-11-30T16:14:15.000+0000\"},{\"attributes\":{\"type\":\"Meeting__c\",\"url\":\"/services/data/v43.0/sobjects/Meeting__c/a495C0000004KTXQA2\"},\"Name\":\"Meeting-000117\",\"LastModifiedDate\":\"2018-11-30T16:14:15.000+0000\"},{\"attributes\":{\"type\":\"Meeting__c\",\"url\":\"/services/data/v43.0/sobjects/Meeting__c/a495C0000004KTYQA2\"},\"Name\":\"Meeting-000118\",\"LastModifiedDate\":\"2018-11-30T16:14:15.000+0000\"},{\"attributes\":{\"type\":\"Meeting__c\",\"url\":\"/services/data/v43.0/sobjects/Meeting__c/a495C0000004KTZQA2\"},\"Name\":\"Meeting-000119\",\"LastModifiedDate\":\"2018-11-30T16:14:15.000+0000\"}]}")
        ]);
        $handler = HandlerStack::create($mock);
        $mock = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $mock);

        $client = app(SalesForceClient::class);
        $response = $client->query($this->query);
        $this->assertTrue($response['done']);
    }

    /**
     * @covers \App\SalesForceClient::token
     */
    public function testExpiredToken()
    {
        $mock = new MockHandler([
            new Response(401, [], "[{\"message\":\"Session expired or invalid\",\"errorCode\":\"INVALID_SESSION_ID\"}]"),
            new Response(200, [], "{\"access_token\":\"00D5C000000LOtI!ARMAQO0V8C30qnwN2J3GPz9IOkdnQcP1ZSXp3abyzpRYJ92ORwLodp5s7D783BIy1IFrzOWSZwo_fRaY538nMc2SP7oO29u_\",\"instance_url\":\"https://pfizerbt--Haigang.cs62.my.salesforce.com\",\"id\":\"https://test.salesforce.com/id/00D5C000000LOtIUAW/0055C000000ooQHQAY\",\"token_type\":\"Bearer\",\"issued_at\":\"1543873716850\",\"signature\":\"A+i8lz+UTUTDwF9lk28LIB11oyPJAcPMVkdl5ANbL7E=\"}"),
            new Response(200, [], "{\"totalSize\":10,\"done\":true,\"records\":[{\"attributes\":{\"type\":\"Meeting__c\",\"url\":\"/services/data/v43.0/sobjects/Meeting__c/a495C0000004KTQQA2\"},\"Name\":\"Meeting-000110\",\"LastModifiedDate\":\"2018-11-30T16:14:15.000+0000\"},{\"attributes\":{\"type\":\"Meeting__c\",\"url\":\"/services/data/v43.0/sobjects/Meeting__c/a495C0000004KTRQA2\"},\"Name\":\"Meeting-000111\",\"LastModifiedDate\":\"2018-11-30T16:14:15.000+0000\"},{\"attributes\":{\"type\":\"Meeting__c\",\"url\":\"/services/data/v43.0/sobjects/Meeting__c/a495C0000004KTSQA2\"},\"Name\":\"Meeting-000112\",\"LastModifiedDate\":\"2018-11-30T16:14:15.000+0000\"},{\"attributes\":{\"type\":\"Meeting__c\",\"url\":\"/services/data/v43.0/sobjects/Meeting__c/a495C0000004KTTQA2\"},\"Name\":\"Meeting-000113\",\"LastModifiedDate\":\"2018-11-30T16:14:15.000+0000\"},{\"attributes\":{\"type\":\"Meeting__c\",\"url\":\"/services/data/v43.0/sobjects/Meeting__c/a495C0000004KTUQA2\"},\"Name\":\"Meeting-000114\",\"LastModifiedDate\":\"2018-11-30T16:14:15.000+0000\"},{\"attributes\":{\"type\":\"Meeting__c\",\"url\":\"/services/data/v43.0/sobjects/Meeting__c/a495C0000004KTVQA2\"},\"Name\":\"Meeting-000115\",\"LastModifiedDate\":\"2018-11-30T16:14:15.000+0000\"},{\"attributes\":{\"type\":\"Meeting__c\",\"url\":\"/services/data/v43.0/sobjects/Meeting__c/a495C0000004KTWQA2\"},\"Name\":\"Meeting-000116\",\"LastModifiedDate\":\"2018-11-30T16:14:15.000+0000\"},{\"attributes\":{\"type\":\"Meeting__c\",\"url\":\"/services/data/v43.0/sobjects/Meeting__c/a495C0000004KTXQA2\"},\"Name\":\"Meeting-000117\",\"LastModifiedDate\":\"2018-11-30T16:14:15.000+0000\"},{\"attributes\":{\"type\":\"Meeting__c\",\"url\":\"/services/data/v43.0/sobjects/Meeting__c/a495C0000004KTYQA2\"},\"Name\":\"Meeting-000118\",\"LastModifiedDate\":\"2018-11-30T16:14:15.000+0000\"},{\"attributes\":{\"type\":\"Meeting__c\",\"url\":\"/services/data/v43.0/sobjects/Meeting__c/a495C0000004KTZQA2\"},\"Name\":\"Meeting-000119\",\"LastModifiedDate\":\"2018-11-30T16:14:15.000+0000\"}]}")
        ]);
        $handler = HandlerStack::create($mock);
        $mock = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $mock);

        Cache::forever('access_token', 'xxxx');
        $client = app(SalesForceClient::class);
        $response = $client->query($this->query);
        $this->assertTrue($response['done']);
    }

    /**
     * @covers \App\SalesForceClient::uploadFile
     */
    public function testUpload()
    {
        $mock = new MockHandler([
            new Response(200, [], "")
        ]);

        $handler = HandlerStack::create($mock);
        $mock = new Client(['handler' => $handler]);
        $this->app->instance(Client::class, $mock);
        Storage::fake();
        Storage::shouldReceive("disk")->andReturnSelf();
        Storage::shouldReceive("get")->once()->andReturn("foo");
        Storage::shouldReceive("exists")->once()->andReturnFalse();
        $client = app(SalesForceClient::class);
        $file_name = "foo.txt";
        $projectId = 555;
        $type = "bar";
        $source_path = storage_path("app/public/project-attachments/foo.txt");
        $client->uploadFile($file_name, $projectId, $type, $source_path);
    }
}
