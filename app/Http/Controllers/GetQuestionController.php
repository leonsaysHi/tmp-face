<?php

namespace App\Http\Controllers;

use App\SurveyRepository;
use Illuminate\Http\Request;

class GetQuestionController extends Controller
{

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param SurveyRepository $repository
     * @return \Illuminate\Http\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __invoke(Request $request, SurveyRepository $repository)
    {
        return response()->json($repository->getQuestion());
    }
}
