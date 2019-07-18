<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class APITestControllerTest extends TestCase
{
    /**
     * @covers App\Http\Controllers\APITestController::__invoke
     */
    public function testApiTester()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->get('apiviewer')->assertStatus(302);
    }

    /**
     * @covers App\Http\Controllers\APITestController::masquerade
     */
    public function testMasquerade()
    {
        $user = factory(\App\User::class)->create();
        Auth::setUser($user);
        $this->be($user);
        $this->get('/masquerade', ['guid' => null])->assertStatus(302);
        $this->assertEquals(null, $user->viewas_guid);
    }

    /**
     * @covers App\Http\Controllers\APITestController::endMasquerade
     */
    public function testEndMasquerade()
    {
        $user = factory(\App\User::class)->create();
        Auth::setUser($user);
        $this->be($user);
        $this->get('/end-masquerade')->assertStatus(302);
        $this->assertEquals(null, $user->viewas_guid);
        $this->assertEquals(null, $user->role);
    }

    /**
     * @covers App\Http\Controllers\APITestController::setTester
     */
    public function testSetTester()
    {
        $user = factory(\App\User::class)->create();
        Auth::setUser($user);
        $this->be($user);
        $this->get('set-tester')->assertStatus(302);
    }

    /**
     * @covers App\Http\Controllers\APITestController::unCache
     */
    public function testUnCache()
    {
        $user = factory(\App\User::class)->create();
        Auth::setUser($user);
        $this->be($user);

        $this->get('uncache/all')->assertStatus(200);

        Cache::shouldReceive('has')
            ->andReturn(['foo']);

        Cache::shouldReceive('forget')
            ->andReturn([]);

        $this->get('uncache/foo')->assertStatus(200);
    }
}
