<?php

namespace Tests\Feature;

use App\Http\Controllers\UserPreferencesController;
use App\User;
use App\UserPreferencesRepository;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPreferencesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $user = new User();
        $user->name = 'Test User';
        $user->guid = 'xxxx';
        $user->id = 1;
        Auth::setUser($user);
    }

    /**
     * @covers \App\Http\Controllers\UserPreferencesController::getPreferences
     * @covers \App\Http\Controllers\UserPreferencesController::__construct
     */
    public function testGetPreferences()
    {
        $mock = new UserPreferencesRepository();
        $this->app->instance(UserPreferencesRepository::class, $mock);

        $shouldReturn = [
            "columnsSelected" => [
                'projectId', 'projectName', 'status', 'projectType', 'LastModifiedDate'
            ],
            "sortBy" => 'LastModifiedDate',
            "perPage" => "5"
        ];


        $this->get('api/get-preferences/dashboard')
            ->assertStatus(200)
            ->assertJson($shouldReturn);
    }

    /**
     * @covers \App\Http\Controllers\UserPreferencesController::setPreferences
     * @covers \App\Http\Controllers\UserPreferencesController::__construct
     */
    public function testSetPreferences()
    {
        $mockData = [
            "columnsSelected" => [
                "status",
            ],
            'perPage' => "3",
            'sortBy' => 'status'
        ];

        $shouldReturn = [
            "columnsSelected" => [
                "status"
            ],
            "perPage" => "3",
            "sortBy" => "status"

        ];

        $repository = new UserPreferencesRepository();
        $this->app->instance(UserPreferencesRepository::class, $repository);

        $mock = new UserPreferencesController($repository);

        $mock->getPreferences();

        $this->json('POST', 'api/set-preferences/dashboard', $mockData)
            ->assertStatus(200)
            ->assertJson($shouldReturn);
    }
}
