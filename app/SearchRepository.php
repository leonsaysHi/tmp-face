<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SearchRepository
{
    protected $repository;

    public function __construct(ProjectSearchRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $recordCount
     * @param $user
     * @return mixed
     */
    public function historyIndex($recordCount, $user)
    {
        $recordCount = $recordCount === 0 ? count($user
            ->searches()
            ->get())
            : $recordCount;

        return $user
            ->searches()
            ->orderBy('created_at', 'desc')
            ->take($recordCount)
            ->get();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $search = Search::findOrFail($id);
        $search = $search->toArray();

        $useCached = false;
        $filters = Arr::get($search, "filters", []);

        $includeDraft = cast_to_bool((Arr::get($search, 'include_draft', true)));
        $includePending = cast_to_bool((Arr::get($search, 'include_pending', true)));
        $includeActive = cast_to_bool((Arr::get($search, 'include_active', true)));
        $includeActiveFuture = cast_to_bool((Arr::get($search, 'include_active', true)));
        $includeCancelled = cast_to_bool(Arr::get($search, 'include_cancelled', false));
        $includeCompleted = cast_to_bool(Arr::get($search, 'include_completed', true));
        $projectStartYearSearchText =
            Arr::get($search, 'project_start_year_search_text', (string) Carbon::now()->year);

        $basicSearchText = Arr::get($search, 'basic_search_text', null);

        $projectNameSearchText = Arr::get($search, 'project_name_search_text', null);
        $fullTextSearchText = Arr::get($search, 'full_text_search_text', null);
        $projectDescriptionSearchText = Arr::get($search, 'project_description_search_text', null);
        $projectOwnerSearchText = Arr::get($search, 'project_owner_search_text', null);

        $startDateSearchBegin = Arr::get($search, 'start_date_search_begin', null);
        $startDateSearchEnd = Arr::get($search, 'start_date_search_end', null);
        $endDateSearchBegin = Arr::get($search, 'end_date_search_begin', null);
        $endDateSearchEnd = Arr::get($search, 'end_date_search_end', null);

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
            'endDateSearchEnd',
            'useCached',
            'filters'
        );

        return response()->json(
            array_merge($this->repository->search($payload), $search)
        );
    }

    /**
     * @param $request
     * @param $id
     * @return Search
     */
    public function savedStore($request, $id)
    {
        DB::beginTransaction();

        $search = new Search();
        $search->basic_search_text = Arr::get($request, 'basicSearchText', null);
        $search->include_draft = Arr::get($request, 'includeDraft', true);
        $search->include_pending = Arr::get($request, 'includePending', true);
        $search->include_active = Arr::get($request, 'includeActive', true);
        $search->include_cancelled = Arr::get($request, 'includeCancelled', true);
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
        $search->is_saved = Arr::get($request, 'is_saved', true);
        $search->user_id =  $id;
        $search->is_advanced = is_null(Arr::get($request, 'basicSearchText', null)) ? true : false;
        $search->save();

        DB::commit();

        return $search;
    }

    /**
     * @param $user
     * @return mixed
     */
    public function savedIndex($user)
    {
        return $user
            ->searches()
            ->whereIsSaved(true)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function savedDestroy($id)
    {
        $search = Search::findOrFail($id);

        DB::beginTransaction();
        $search->delete();
        DB::commit();

        return response(['message' => 'Search record successfully removed.']);
    }
}
