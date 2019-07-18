<?php

namespace Tests\Contract;

use App\SalesForceClient;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Class SalesForceClientContractTest
 * @package Tests\Contract
 */
class SalesForceClientContractTest extends TestCase
{
    /**
     * @var string
     */
    private $query = 'Placeholder Query';

    public function setUp(): void
    {
        parent::setUp();
        $user = new User();
        $user->name = 'Test User';
        $user->guid = 'xxxx';
        Auth::setUser($user);
    }

    public function testGenerateToken()
    {
        Cache::forget('access_token');
        $client = app()->make(SalesForceClient::class);
        $client->generateToken();
        $this->assertNotNull(Cache::get('access_token'));
    }

    public function testAccessApi()
    {
        $client = app(SalesForceClient::class);
        $response = $client->query($this->query);
        $this->assertTrue($response['done']);
    }

    public function testDescribe()
    {
        $client = app(SalesForceClient::class);
        $response = $client->describe('Meeting__c');
        $this->assertNotNull($response['fields']);
    }

    public function testQueryMeetings()
    {
        $client = app(SalesForceClient::class);
        $response = $client->query($this->query);
        $this->assertNotEmpty($response['records']);
    }

    public function testExpiredToken()
    {
        Cache::forever('access_token', 'xxxx');
        $client = app(SalesForceClient::class);
        $response = $client->query($this->query);
        $this->assertTrue($response['done']);
    }
}
