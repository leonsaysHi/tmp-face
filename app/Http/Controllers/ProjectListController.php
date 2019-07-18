<?php

namespace App\Http\Controllers;

use App\ProjectListRepository;
use Illuminate\Http\Request;

class ProjectListController extends Controller
{
    /**
     * Return the list of projects
     *
     * @param ProjectListRepository $repository
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(ProjectListRepository $repository, Request $request)
    {
        $input = $this->validate($request, [
            'filters' => 'array',
            'includeTeam' => 'nullable',
            'includeDraft' => 'nullable',
            'includePending' => 'nullable',
            'includeActive' => 'nullable',
            'includeCancelled' => 'nullable',
            'includeCompleted' => 'nullable',
            'projectStartYearSearchText' => 'nullable',
            'useCached' => 'nullable|boolean',
        ]);


        return response()->json($repository->projectList($input));
    }
}
