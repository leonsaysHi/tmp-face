<?php

namespace App\Http\Controllers;

use App\SurveyRepository;
use Illuminate\Http\Request;

class SetSurveyResultController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param SurveyRepository $repository
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __invoke(Request $request, SurveyRepository $repository)
    {
        $input = $this->validate($request, [
            'projectSfdcId' => 'string',
            'userSfdcId' => 'string',
            'results' => 'array',
            'results.*.questionSfdcId' => 'string',
            'results.*.rate' => 'int',
            'results.*.comments' => 'string|nullable'
        ]);

        return response()->json($repository->setResults($input));
    }
}
