<?php

namespace App;

use App\Traits\FilterTrait;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ProjectListRepository extends SalesForceRepository
{
    use FilterTrait;

    /**
     * Make the call to SF and cache it for the user.
     *
     * If cache exists return cached data,
     * if cache exists and request has filters, apply the filters on the cached data return data,
     * if cache does not exist, make the call to SF, cache it, return with applied filters.
     *
     * @param array $payload
     * @return mixed
     */
    public function projectList($payload = [])
    {
        $useCached = Arr::get($payload, "useCached", true);
        $filters = Arr::get($payload, "filters", []);

        $includeTeam = cast_to_bool((Arr::get($payload, 'includeTeam', false)));
        $includeDraft = cast_to_bool((Arr::get($payload, 'includeDraft', true)));
        $includePending = cast_to_bool((Arr::get($payload, 'includePending', true)));
        $includeActive = cast_to_bool((Arr::get($payload, 'includeActive', true)));
        $includeActiveFuture = cast_to_bool((Arr::get($payload, 'includeActive', true)));
        $includeCancelled = cast_to_bool(Arr::get($payload, 'includeCancelled', false));
        $includeCompleted = cast_to_bool(Arr::get($payload, 'includeCompleted', false));
        $projectStartYearSearchText =
            Arr::get($payload, 'projectStartYearSearchText', (string) Carbon::now()->year);

        $payload = compact(
            'includeTeam',
            'includeCancelled',
            'includeCompleted',
            'includeDraft',
            'includePending',
            'includeActive',
            'includeActiveFuture',
            'projectStartYearSearchText'
        );

        if (!$useCached) {
            $result = $this->makeSalesForceCall($payload, 'project-list', Auth::id());
        } else {
            $result = $this->getCachedData("project-list", Auth::id());
        }

        if (!empty($filters)) {
            $result['projects'] = $this->filterRecords($result['projects'], $filters);
        }

        return $result;
    }
}
