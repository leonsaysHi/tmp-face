<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\SalesForceRepository;
use Illuminate\Support\Facades\App;

class SalesForceControllerTest extends TestCase
{

    /**
     * @covers \App\Http\Controllers\SalesForceController::query
     */
    public function testQuery()
    {
        $this->withoutMiddleware();
        $repo = \Mockery::mock(SalesForceRepository::class);
        $repo->shouldReceive("query")->once()->andReturn(true);
        App::instance(SalesForceRepository::class, $repo);
        $this->json("GET", "/api/salesforce/query?query=foo")->assertStatus(202);
    }

    /**
     * @covers \App\Http\Controllers\SalesForceController::create
     */
    public function testCreate()
    {
        $this->withoutMiddleware();
        $repo = \Mockery::mock(SalesForceRepository::class);
        $repo->shouldReceive("create")->once()->andReturn(true);
        App::instance(SalesForceRepository::class, $repo);
        $this->json("POST", "/api/salesforce/create", [
            'object' => "foo",
            'data' => ['foo' => "bar"]
        ])->assertStatus(202);
    }

    /**
     * @covers \App\Http\Controllers\SalesForceController::update
     */
    public function testUpdate()
    {
        $this->withoutMiddleware();
        $repo = \Mockery::mock(SalesForceRepository::class);
        $repo->shouldReceive("update")->once()->andReturn(true);
        App::instance(SalesForceRepository::class, $repo);
        $this->json("PATCH", "/api/salesforce/update", [
            'object' => "foo",
            "id" => "10",
            'data' => ['foo' => "bar"]
        ])->assertStatus(202);
    }


    /**
     * @covers \App\Http\Controllers\SalesForceController::delete
     */
    public function testDelete()
    {
        $this->withoutMiddleware();
        $repo = \Mockery::mock(SalesForceRepository::class);
        $repo->shouldReceive("delete")->once()->andReturn(true);
        App::instance(SalesForceRepository::class, $repo);
        $this->json("POST", "/api/salesforce/delete", [
            'object' => "foo",
            "id" => "10"
        ])->assertStatus(202);
    }
}
