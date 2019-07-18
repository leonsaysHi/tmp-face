<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectDetailsViewControllerTest extends TestCase
{
    use RefreshDatabase;

     /**
     * @covers \App\Http\Controllers\ProjectDetailsViewController::__invoke
     */
    public function testProjectDetails()
    {
        $user = factory(\App\User::class)->create();
        $this->be($user);
        $this->get('/project-details/1')->assertStatus(200);
    }
}
