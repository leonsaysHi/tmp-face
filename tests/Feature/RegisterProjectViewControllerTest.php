<?php

namespace Tests\Feature;

use Tests\TestCase;

class RegisterProjectViewControllerTest extends TestCase
{
    /**
     * @covers \App\Http\Controllers\RegisterProjectViewController::__invoke
     */
    public function testRegisterProject()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->get('/register-project')->assertStatus(200);
    }
}
