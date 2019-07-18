<?php

namespace App\Http\Controllers;

use App\UserRepository;

class GetUserController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param UserRepository $repository
     * @return \Illuminate\Http\Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __invoke(UserRepository $repository)
    {
        return response()->json($repository->get());
    }
}
