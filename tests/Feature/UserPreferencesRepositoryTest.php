<?php

namespace Tests\Feature;

use App\Http\Controllers\UserPreferencesController;
use App\UserPreferencesRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class UserPreferencesRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @covers App\UserPreferencesRepository::getUserPreferences
     * @covers App\UserPreferencesRepository::setDefaultUserPreferencesForFirstLanding
     * @covers App\UserPreferencesRepository::prepareUserPreferencesResponse
     * @covers App\UserPreferencesRepository::getUserPreferencesAndSortByOrder
     * @covers App\UserPreferencesRepository::getAdditionalValueFromGivenPreference
     */
    public function testGetPreferences()
    {
        $user = factory(\App\User::class)->create();
        $user->id = 1;
        Auth::setUser($user);
        $this->be($user);

        $mock = new UserPreferencesRepository();
        $this->app->instance(UserPreferencesRepository::class, $mock);

        $shouldReturn = [
            "columnsSelected" => [
                'projectId', 'projectName', 'status', 'projectType', 'LastModifiedDate'
            ],
            "sortBy" => "LastModifiedDate",
            "perPage" => "5",
            "sortDesc" => true,
        ];

        $mockData = $mock->getUserPreferences($user, 'dashboard');

        $this->assertEquals($mockData, $shouldReturn);
    }

    /**
     * @covers App\UserPreferencesRepository::setUserPreferences
     * @covers App\UserPreferencesRepository::prepareUserPreferencesResponse
     * @covers App\UserPreferencesRepository::getUserPreferencesAndSortByOrder
     * @covers App\UserPreferencesRepository::getAdditionalValueFromGivenPreference
     * @covers App\UserPreferencesRepository::checkIfKeyExistsInRequestAndUpdateExistingPivot
     */
    public function testSetPreferences()
    {
        $user = factory(\App\User::class)->create();
        $user->id = 1;
        Auth::setUser($user);
        $this->be($user);

        $mockData = [
            "columnsSelected" => [
                "status",
            ],
            'perPage' => "3",
            'sortBy' => 'status',
            'sortDesc' => true
        ];

        $shouldReturn = [
            "columnsSelected" => [
                "status"
            ],
            "perPage" => "3",
            "sortBy" => "status",
            "sortDesc" => true

        ];
        $repository = new UserPreferencesRepository();
        $this->app->instance(UserPreferencesRepository::class, $repository);

        $mock = new UserPreferencesController($repository);

        $mock->getPreferences();

        $mockData = $repository->setUserPreferences($mockData, $user, 'dashboard');

        $this->assertEquals($mockData, $shouldReturn);
    }
}
