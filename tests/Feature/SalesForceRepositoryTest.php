<?php

namespace Tests\Feature;

use GuzzleHttp\Client;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Tests\TestCase;
use App\SalesForceClient;
use Illuminate\Support\Facades\App;
use App\SalesForceRepository;

class SalesForceRepositoryTest extends TestCase
{
    /**
     * @covers \App\SalesForceRepository::post
     */
    public function testPost()
    {
        $mock = \Mockery::mock(SalesForceClient::class);
        $mock->shouldReceive("post")->once()->andReturn(true);
        App::instance(SalesForceClient::class, $mock);
        $repo = App::make(SalesForceRepository::class);
        $this->assertTrue($repo->post("foo"));
    }

    /**
     * @covers \App\SalesForceRepository::query
     */
    public function testQuery()
    {
        $mock = \Mockery::mock(SalesForceClient::class);
        $mock->shouldReceive("query")->once()->andReturn(true);
        App::instance(SalesForceClient::class, $mock);
        $repo = App::make(SalesForceRepository::class);
        $this->assertTrue($repo->query("foo"));
    }

    /**
     * @covers \App\SalesForceRepository::create
     */
    public function testCreate()
    {
        $mock = \Mockery::mock(SalesForceClient::class);
        $mock->shouldReceive("create")->once()->andReturn(true);
        App::instance(SalesForceClient::class, $mock);
        $repo = App::make(SalesForceRepository::class);
        $this->assertTrue($repo->create("foo", []));
    }

    /**
     * @covers \App\SalesForceRepository::update
     */
    public function testUpdate()
    {
        $mock = \Mockery::mock(SalesForceClient::class);
        $mock->shouldReceive("update")->once()->andReturn(true);
        App::instance(SalesForceClient::class, $mock);
        $repo = App::make(SalesForceRepository::class);
        $this->assertEquals('OK', $repo->update("foo", "1111", []));
    }

    /**
     * @covers \App\SalesForceRepository::delete
     */
    public function testDelete()
    {
        $mock = \Mockery::mock(SalesForceClient::class);
        $mock->shouldReceive("delete")->once()->andReturn(true);
        App::instance(SalesForceClient::class, $mock);
        $repo = App::make(SalesForceRepository::class);
        $this->assertEquals('OK', $repo->delete("foo", "1111", []));
    }
}
