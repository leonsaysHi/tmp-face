<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Policies\UserPolicy;

class UserPolicyTest extends TestCase
{
    use RefreshDatabase;

    public $user;

    public $admin;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(\App\User::class)->create();
        $this->admin = factory(\App\User::class)
            ->state('admin')->create();
    }

    public function testIndex()
    {
        $policy = new UserPolicy();

        $this->assertTrue($policy->viewIndex($this->admin));
        $this->assertFalse($policy->viewIndex($this->user));
    }

    public function testSearch()
    {
        $policy = new UserPolicy();
        $this->assertTrue($policy->search($this->admin));
        $this->assertFalse($policy->search($this->user));
    }

    public function testCreate()
    {
        $policy = new UserPolicy();
        $user = factory(\App\User::class)->create();
        $admin = factory(\App\User::class)
            ->state('admin')->create();
        $this->assertTrue($policy->create($this->admin));
        $this->assertFalse($policy->create($this->user));
    }
}
