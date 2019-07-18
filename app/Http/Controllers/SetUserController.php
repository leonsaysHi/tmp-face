<?php

namespace App\Http\Controllers;

use App\UserRepository;
use Illuminate\Http\Request;

class SetUserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param UserRepository $repository
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __invoke(UserRepository $repository, Request $request)
    {
        $input = $this->validate($request, [
            'userCostCenter' => 'nullable',
            'brands' => 'nullable',
            'projectFilter' => 'nullable',
            'CommunicationFrequency' => 'nullable',
            'delegates' => 'nullable',
            'delegates.*.sfdcId' => 'nullable',
        ]);

        return response()->json($repository->set($input));
    }
}
