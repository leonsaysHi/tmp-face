<?php

namespace App\Http\Controllers;

use App\ProjectSearchRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProjectSearchController
 * @package App\Http\Controllers
 */
class ProjectSearchController extends Controller
{
    /**
     * Get projects based on the user search
     * and save the search criteria as user search history
     *
     * @param ProjectSearchRepository $repository
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */

    public function __invoke(ProjectSearchRepository $repository, Request $request)
    {
        $input = $this->validate($request, [
            'basicSearchText' => 'nullable|string|min:2',

            'projectNameSearchText' => 'nullable|string|min:2',
            'fullTextSearchText' => 'nullable|string|min:2',
            'projectDescriptionSearchText' => 'nullable|string',
            'projectOwnerSearchText' => 'nullable|string',
            'startDateSearchBegin' => 'nullable|string',
            'startDateSearchEnd' => 'nullable|string',
            'endDateSearchBegin' => 'nullable|string',
            'endDateSearchEnd' => 'nullable|string',

            'filters' => 'nullable|array',
            'is_saved' => 'nullable|boolean',
            'includeDraft' => 'nullable|boolean',
            'includePending' => 'nullable|boolean',
            'includeActive' => 'nullable|boolean',
            'includeCancelled' => 'nullable|boolean',
            'includeCompleted' => 'nullable|boolean',
            'projectStartYearSearchText' => 'nullable|string',
            'useCached' => 'nullable|boolean',
        ]);

        $repository->saveSearchRecordUpToMaxGivenValue(Auth::user(), $input, 40);

        return response()->json($repository->search($input));
    }
}
