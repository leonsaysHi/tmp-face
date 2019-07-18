<?php

namespace App\Http\Controllers;

use App\GetLinkRepository;

class GetLinkController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param GetLinkRepository $repository
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __invoke(GetLinkRepository $repository)
    {
        return response()->json($repository->getLink());
    }
}
