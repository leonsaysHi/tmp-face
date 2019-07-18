<?php

namespace App\Http\Controllers;

use App\ProjectListDetailRepository;
use Illuminate\Http\Request;

class GetProjectListDetailController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param ProjectListdetailRepository $repository
     * @return \Illuminate\Http\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(Request $request, ProjectListDetailRepository $repository)
    {
        $input = $this->validate($request, [
            'projectSfdcIds' => 'array',
            'projectSfdcIds.*' => 'string',
        ]);

        return response()->json($repository->getProjectListDetails($input));
    }
}
