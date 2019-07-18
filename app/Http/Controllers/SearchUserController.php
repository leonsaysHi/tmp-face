<?php

namespace App\Http\Controllers;

use App\UserRepository;
use Illuminate\Http\Request;

class SearchUserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param UserRepository $repository
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function __invoke(UserRepository $repository, Request $request)
    {
        $input = $this->validate($request, [
            'searchText' => 'string',
            'searchResource' => 'boolean|nullable',
        ]);

        return response()->json($repository->search($input));
    }
}
