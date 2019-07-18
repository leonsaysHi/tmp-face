<?php

namespace App\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

trait FilterTrait
{

    /**
     * Return user related cached data based on the platform.
     *
     * @param $platform
     * @param $userId
     * @return mixed
     */
    public function getCachedData($platform, $userId)
    {
        return Cache::get($platform . '_' . $userId);
    }

    /**
     * Make an API call to SalesForce endpoint with given payload.
     *
     * @param $payload
     * @param $platform
     * @param $userId
     * @return mixed
     */
    public function makeSalesForceCall($payload, $platform, $userId)
    {
        $endPoint = $platform === 'search' ? 'endpoints.search' : 'endpoints.project_list';

        $response = $this->post(config($endPoint), $payload);

        $result = $this->removeNullFiltersAndAttachToResponse($response);

        Cache::put($platform . '_' . $userId, $result, 3600);

        return $result;
    }

    /**
     * Discard filters that are null
     * and attach the filter arr to response.
     *
     * @param $response
     * @return mixed
     */
    private function removeNullFiltersAndAttachToResponse($response)
    {
        if (!is_array($response)) {
            return $response;
        }

        if (empty($response['projects'])) {
            $response['filterable'] = [];
            return $response;
        }

        $response['filterable'] = $this->generateFilters($response);

        foreach ($response['filterable'] as $key => $value) {
            if (is_null($value) || $value == '') {
                unset($response['filterable'][$key]);
            }
        }

        return $response;
    }

    /**
     * Assign each filterable fields to their keys.
     *
     * @param $data
     * @return \Illuminate\Support\Collection|mixed
     */
    private function generateFilters($data)
    {
        $projects = Arr::get($data, 'projects', []);

        if (empty($projects)) {
            return $projects;
        }

        $projects = collect($projects);

        $filterableFields = [
            'TagTherapeuticArea',
            'TagBUStakeholder',
            'TagClientTactic',
            'TagMarket',
            'TagMethodology',
            'TagProduct',
            'TagResearchType',
            'TagRespondentType',
            'projectOwnerName',
            'projectType'
        ];

        $filterable = [];

        foreach ($filterableFields as $field) {
            $filterable[$field] = $this->explodeAndReturnUnique($projects, $field);
        }

        return $filterable;
    }

    /**
     * Create a unique valued array for given pipe separated value
     * and/or return year string from given date field.
     *
     * @param $arr
     * @param $field
     * @return array
     */
    private function explodeAndReturnUnique($arr, $field)
    {
        $newArr = $arr->pluck($field)
            ->filter()
            ->unique()
            ->map(function ($item) use ($field) {
                return explode('|', $item);
            })->all();

        if (!empty($newArr)) {
            return array_values(array_unique(array_merge(...$newArr)));
        }
    }

    /**
     * Filter the data with given filter options.
     *
     * @param $data
     * @param $filtersGroup
     * @return \Illuminate\Support\Collection
     */
    public function filterRecords($data, $filtersGroup)
    {
        $data = collect($data);

        if (!empty($filtersGroup)) {
            $filtered = $data->filter(function ($value) use ($filtersGroup) {
                return $this->filterConditionsMatched($value, $filtersGroup);
            });

            return $filtered;
        }

        return $data;
    }

    /**
     * Determine if given data matches with filterable fields.
     *
     * ex: same field: apply OR condition, different fields: apply AND condition.
     * Return false if one of the given conditions does not match.
     * @param $value
     * @param $filtersGroup
     * @return bool
     */
    private function filterConditionsMatched($value, $filtersGroup)
    {
        $conditions = [];

        foreach ($filtersGroup as $groupKey => $filterGroup) {
            if (count($filterGroup) > 1) {
                foreach ($filterGroup as $filter) {
                    $orValue[] = isset($value[$groupKey]) && Str::contains($value[$groupKey], $filter);
                }
                $conditions[] = in_array(true, $orValue);
            } else {
                $conditions[] = isset($value[$groupKey]) && Str::contains($value[$groupKey], $filterGroup);
            }
        }

        if (!empty($conditions)) {
            return !in_array(false, $conditions);
        }

        return false;
    }
}
