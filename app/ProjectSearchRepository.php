<?php

namespace App;

use App\Traits\FilterTrait;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectSearchRepository extends SalesForceRepository
{
    use FilterTrait;

    /**
     * @param array $payload
     * @return mixed
     */
    public function search($payload = [])
    {
        $useCached = cast_to_bool(Arr::get($payload, "useCached", true));
        $filters = Arr::get($payload, "filters", []);

        $includeDraft = cast_to_bool((Arr::get($payload, 'includeDraft', true)));
        $includePending = cast_to_bool((Arr::get($payload, 'includePending', true)));
        $includeActive = cast_to_bool((Arr::get($payload, 'includeActive', true)));
        $includeActiveFuture = cast_to_bool((Arr::get($payload, 'includeActive', true)));
        $includeCancelled = cast_to_bool(Arr::get($payload, 'includeCancelled', false));
        $includeCompleted = cast_to_bool(Arr::get($payload, 'includeCompleted', true));
        $projectStartYearSearchText =
            Arr::get($payload, 'projectStartYearSearchText', (string) Carbon::now()->year);

        $basicSearchText = Arr::get($payload, 'basicSearchText', null);

        $projectNameSearchText = Arr::get($payload, 'projectNameSearchText', null);
        $fullTextSearchText = Arr::get($payload, 'fullTextSearchText', null);
        $projectDescriptionSearchText = Arr::get($payload, 'projectDescriptionSearchText', null);
        $projectOwnerSearchText = Arr::get($payload, 'projectOwnerSearchText', null);

        $startDateSearchBegin = Arr::get($payload, 'startDateSearchBegin', null);
        $startDateSearchEnd = Arr::get($payload, 'startDateSearchEnd', null);
        $endDateSearchBegin = Arr::get($payload, 'endDateSearchBegin', null);
        $endDateSearchEnd = Arr::get($payload, 'endDateSearchEnd', null);

        $payload = compact(
            'includeCancelled',
            'includeCompleted',
            'includeDraft',
            'includePending',
            'includeActive',
            'includeActiveFuture',
            'projectStartYearSearchText',
            'basicSearchText',
            'projectNameSearchText',
            'fullTextSearchText',
            'projectDescriptionSearchText',
            'projectOwnerSearchText',
            'startDateSearchBegin',
            'startDateSearchEnd',
            'endDateSearchBegin',
            'endDateSearchEnd'
        );

        if (!$useCached) {
            $result = $this->makeSalesForceCall($payload, 'search', Auth::id());
        } else {
            $result = $this->getCachedData('search', Auth::id());
        }

        if (!empty($filters)) {
            $result['projects'] = $this->filterRecords($result['projects'], $filters);
        }

        $result['totalProject'] = count(Arr::get($result, 'projects', []));

        return $result;
    }

    /**
     * @param $user
     * @param $input
     * @param $value
     */
    public function saveSearchRecordUpToMaxGivenValue($user, $input, $value)
    {
        $searchRecords = $user->searches->where('is_saved', 0)->toArray();

        if (!empty(array_filter($searchRecords)) && count($searchRecords) >= $value) {
            $this->removeLastSearchRecord($user);
        }

        $this->saveSearchRecord($user, $input);
    }

    /**
     * @param $user
     * @param $request
     */
    protected function saveSearchRecord($user, $request)
    {
        DB::beginTransaction();

        $search = new Search();
        $search->basic_search_text = Arr::get($request, 'basicSearchText', null);
        $search->include_draft = Arr::get($request, 'includeDraft', true);
        $search->include_pending = Arr::get($request, 'includePending', true);
        $search->include_active = Arr::get($request, 'includeActive', true);
        $search->include_cancelled = Arr::get($request, 'includeCancelled', false);
        $search->include_completed = Arr::get($request, 'includeCompleted', true);
        $search->project_start_year_search_text =
            Arr::get($request, 'projectStartYearSearchText', (string) Carbon::now()->year);
        $search->project_name_search_text = Arr::get($request, 'projectNameSearchText', null);
        $search->full_text_search_text = Arr::get($request, 'fullTextSearchText', null);
        $search->project_description_search_text = Arr::get($request, 'projectDescriptionSearchText', null);
        $search->project_owner_search_text = Arr::get($request, 'projectOwnerSearchText', null);
        $search->start_date_search_begin = Arr::get($request, 'startDateSearchBegin', null);
        $search->start_date_search_end = Arr::get($request, 'startDateSearchEnd', null);
        $search->end_date_search_begin = Arr::get($request, 'endDateSearchBegin', null);
        $search->end_date_search_end = Arr::get($request, 'endDateSearchEnd', null);
        $search->filters = Arr::get($request, 'filters', []);
        $search->is_saved = Arr::get($request, 'is_saved', false);
        $search->user_id =  $user->id;
        $search->is_advanced = is_null(Arr::get($request, 'basicSearchText', null)) ? true : false;

        $search->save();

        DB::commit();
    }

    /**
     * @param $user
     */
    protected function removeLastSearchRecord($user)
    {
        DB::beginTransaction();
        $user->searches->where('is_saved', 0)->sortBy('created_at')->values()->first()->delete();

        DB::commit();
    }
}
