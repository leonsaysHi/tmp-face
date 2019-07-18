<?php

namespace App\Http\Controllers;

use App\SAPRepository;
use Illuminate\Http\Request;

class DetachSAPController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param SAPRepository $repository
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __invoke(Request $request, SAPRepository $repository)
    {
        $input = $this->validate($request, [
            'sapSfdcIds' => 'array',
            'sapSfdcIds.*' => 'string'
        ]);

        return response()->json($repository->detach($input));
    }
}
