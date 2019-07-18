<?php

namespace App\Http\Controllers;

use App\ProjectRepository;
use Illuminate\Http\Request;

class CloneProjectController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param ProjectRepository $repository
     * @return \Illuminate\Http\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(Request $request, ProjectRepository $repository)
    {
        $input = $this->validate($request, [
            'projectSfdcIds' => 'array',
            'projectSfdcIds.*' => 'string',
            'cloneTimes' => 'numeric'
            ]);

        return response()->json($repository->cloneRecord($input));
    }
}
