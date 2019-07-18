<?php

namespace App\Http\Controllers;

use App\SearchRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{

    protected $repository;

    public function __construct(SearchRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Return $recordCount amount of latest user search records
     * or return all if no count specified
     *
     * @param int $recordCount
     * @return mixed
     */
    public function historyIndex($recordCount = 0)
    {
        return $this->repository->historyIndex($recordCount, Auth::user());
    }

    /**
     * Return $recordCount amount of latest user search records
     * or return all if no count specified
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return $this->repository->show($id);
    }

    /**
     * Store a search record of the logged in user
     *
     * @param Request $request
     * @return \App\Search
     * @throws \Illuminate\Validation\ValidationException
     */
    public function savedStore(Request $request)
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

        return $this->repository->savedStore($input, Auth::id());
    }

    /**
     * Return all saved searches of the logged in user
     *
     * @return mixed
     */
    public function savedIndex()
    {
        return $this->repository->savedIndex(Auth::user());
    }

    /**
     * Remove a saved record with given id.
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function savedDestroy($id)
    {
        return $this->repository->savedDestroy($id);
    }
}
