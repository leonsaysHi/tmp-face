<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UserPreferencesRepository
{
    protected $user;

    /**
     * @param $user
     * @param $platform
     * @return array
     */
    public function getUserPreferences($user, $platform)
    {
        $this->user = $user;

        $userPreferences = $this->user->preferences()->get();

        if (count($userPreferences) === 0) {
            $this->setDefaultUserPreferencesForFirstLanding();
        }

        return $this->prepareUserPreferencesResponse($platform);
    }

    /**
     * @param $request
     * @param $user
     * @param $platform
     * @return array
     */
    public function setUserPreferences($request, $user, $platform)
    {
        $this->user = $user;
        $ids = [];
        $order = [];


        if (Arr::has($request, 'columnsSelected')) {
            foreach ($request['columnsSelected'] as $key => $preference) {
                $ids[] = Preference::whereValue($preference)->first()->id;
                $order[] = $key;
            }
        }

        $usersPreferences = $this->user->preferences()->wherePivot('platform', $platform)->pluck('value');

        $usersPreferences = array_filter($usersPreferences->toArray(), function ($p) {
            return $p !== 'perPage' && $p !== 'sortBy' && $p !== 'sortDesc';
        });

        DB::beginTransaction();

        foreach ($usersPreferences as $usersPreference) {
            $this->user->preferences()
                ->wherePivot('platform', $platform)
                ->detach(Preference::whereValue($usersPreference)->first()->id);
        }

        if (Arr::has($request, 'columnsSelected')) {
            foreach ($request['columnsSelected'] as $key => $value) {
                $this->user->preferences()
                    ->attach($ids[$key], ['additional_value' => $order[$key], 'platform' => $platform]);
            }
        }

        $this->checkIfKeyExistsInRequestAndUpdateExistingPivot($request, 'perPage', $platform);
        $this->checkIfKeyExistsInRequestAndUpdateExistingPivot($request, 'sortBy', $platform);
        $this->checkIfKeyExistsInRequestAndUpdateExistingPivot($request, 'sortDesc', $platform);

        if (Arr::has($request, 'sortDesc')) {
            $id = Preference::whereValue('sortDesc')->first()->id;
            $this->user->preferences()
                ->wherePivot('platform', $platform)
                ->updateExistingPivot($id, ['additional_value' => $request['sortDesc'], 'platform' => $platform]);
        }

        DB::commit();

        return $this->prepareUserPreferencesResponse($platform);
    }

    /**
     * @param $platform
     * @return array
     */
    protected function prepareUserPreferencesResponse($platform)
    {
        $usersPreferences = $this->user->preferences()->wherePivot('platform', $platform)
            ->pluck('additional_value', 'value');

        $columnsSelected = $this->getUserPreferencesAndSortByOrder($usersPreferences->toArray());


        $perPage = $this->getAdditionalValueFromGivenPreference('perPage', $platform);
        $sortBy = $this->getAdditionalValueFromGivenPreference('sortBy', $platform);
        $sortDesc = $this->getAdditionalValueFromGivenPreference('sortDesc', $platform);
        $sortDesc = $sortDesc ===  "0" ? false : true;

        return [
            'columnsSelected' => array_values($columnsSelected),
            'sortBy' => $sortBy,
            'perPage' => $perPage,
            'sortDesc' => (boolean) $sortDesc,
        ];
    }

    /**
     * @param $arr
     * @return array
     */
    protected function getUserPreferencesAndSortByOrder($arr)
    {

        $filteredPrefs = array_filter_key($arr, function ($p) {
            return $p !== 'perPage' && $p !== 'sortBy' && $p !== 'sortDesc';
        });

        asort($filteredPrefs);

        return array_keys($filteredPrefs);
    }

    /**
     * @param $preference
     * @return mixed
     */
    protected function getAdditionalValueFromGivenPreference($preference, $platform)
    {
        $value = $this->user->preferences()
            ->wherePivot('platform', $platform)
            ->whereValue($preference)
            ->pluck('additional_value')
            ->toArray();
        return Arr::get($value, 0);
    }

    /**
     *
     */
    protected function setDefaultUserPreferencesForFirstLanding()
    {
        $ids = [];
        $preferences = ['projectId', 'projectName', 'status', 'projectType', 'LastModifiedDate'];

        $perPageId = Preference::whereValue('perPage')->first()->id;
        $sortById = Preference::whereValue('sortBy')->first()->id;
        $sortDescId = Preference::whereValue('sortDesc')->first()->id;

        foreach ($preferences as $preference) {
            $ids[] = Preference::whereValue($preference)->first()->id;
        }

        DB::beginTransaction();

        foreach ($ids as $id) {
            $this->user->preferences()->attach($id, ['additional_value' => $id, 'platform' => 'dashboard']);
            $this->user->preferences()->attach($id, ['additional_value' => $id, 'platform' => 'search']);
        }
        $this->user->preferences()->attach($perPageId, ['additional_value' => "5", 'platform' => 'dashboard']);
        $this->user->preferences()->attach($perPageId, ['additional_value' => "5", 'platform' => 'search']);
        $this->user->preferences()->attach($sortDescId, ['additional_value' => true, 'platform' => 'search']);
        $this->user->preferences()
            ->attach($sortById, ['additional_value' => "LastModifiedDate", 'platform' => 'dashboard']);
        $this->user->preferences()
            ->attach($sortById, ['additional_value' => "LastModifiedDate", 'platform' => 'search']);
        $this->user->preferences()->attach($sortDescId, ['additional_value' => true, 'platform' => 'dashboard']);

        DB::commit();
    }

    /**
     * @param $array
     * @param $key
     * @param $platform
     */
    protected function checkIfKeyExistsInRequestAndUpdateExistingPivot($array, $key, $platform)
    {
        if (Arr::has($array, $key)) {
            $id = Preference::whereValue($key)->first()->id;
            $this->user->preferences()
                ->wherePivot('platform', $platform)
                ->updateExistingPivot($id, ['additional_value' => $array[$key], 'platform' => $platform]);
        }
    }
}
