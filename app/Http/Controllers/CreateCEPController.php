<?php

namespace App\Http\Controllers;

use App\CEPRepository;
use Illuminate\Http\Request;

class CreateCEPController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param CEPRepository $repository
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __invoke(Request $request, CEPRepository $repository)
    {
        $input = $this->validate($request, [
            'projectSfdcId' => 'string',
            'CEPId' => 'string',
            'CEPFolderId' => 'string',
            'CEPStatus' => 'string'
        ]);

        return response()->json($repository->createManual($input));
    }
}
