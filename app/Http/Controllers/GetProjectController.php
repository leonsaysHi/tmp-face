<?php

namespace App\Http\Controllers;

use App\ProjectRepository;
use Illuminate\Http\Request;

class GetProjectController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param ProjectRepository $repository
     * @param $projectId
     * @return \Illuminate\Http\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __invoke(Request $request, ProjectRepository $repository, $projectId)
    {
        return response()->json($repository->get($projectId));
    }
}
