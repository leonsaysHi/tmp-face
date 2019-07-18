<?php

namespace App\Http\Controllers;

use App\ProjectRepository;
use Illuminate\Http\Request;

class CreateUpdateProjectController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param ProjectRepository $repository
     * @return \Illuminate\Http\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __invoke(Request $request, ProjectRepository $repository)
    {
        return response()->json($repository->createOrEdit($request->all()));
    }
}
