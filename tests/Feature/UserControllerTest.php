<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    /**
     * @covers \App\Http\Controllers\UserController::__invoke
     */
    public function testExample()
    {
        $user = factory(\App\User::class)->create();
        Auth::setUser($user);
        $this->be($user);

        $response = $this->get('/api/get-user-type');
        $data = json_decode($response->getContent(), true);
        $response->assertStatus(200);
        $this->assertEquals($data['data'], $user->user_type);
    }
}
