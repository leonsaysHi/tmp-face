<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetLovControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @covers App\Http\Controllers\GetLovController::__invoke
     * @covers App\GetLovRepository::getLov
     */
    public function testGetLovViaCache()
    {
        $user = factory(\App\User::class)->create();
        Auth::setUser($user);
        $this->be($user);

        $mockData = [
            'Brand' =>
            [
                "cepI" => null,
                "displayName" => "Foo (bar)",
                "isActive" => true,
                "lovType" => "Brand",
                "myworkId" => null,
                "name" => "foo (bar)",
                "overrideName" => null,
                "parentID" => null,
                "sfdcId" => "a4a5C000000L5hQAS",
                "tmsId" => "266140"
            ],
            [
                "cepI" => null,
                "displayName" => "Foo (Bar)",
                "isActive" => true,
                "lovType" => "Brand",
                "myworkId" => null,
                "name" => "Foo (Bar)",
                "overrideName" => null,
                "parentID" => null,
                "sfdcId" => "a4a5C00000035hHQAS",
                "tmsId" => "266120"
            ],
        ];

        Cache::shouldReceive('remember')
            ->once()
            ->andReturn(json_encode($mockData, 128));

        $json = $this->post('/api/get-lov')
            ->assertStatus(200)
            ->json();

        $this->assertCount(2, $json);
    }
}
