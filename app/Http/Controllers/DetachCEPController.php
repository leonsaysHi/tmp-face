<?php

namespace App\Http\Controllers;

use App\CEPRepository;
use Illuminate\Http\Request;

class DetachCEPController extends Controller
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
            'cepSfdcIds' => 'array',
            'cepSfdcIds.*' => 'string'
        ]);

        return response()->json($repository->detach($input));
    }
}
