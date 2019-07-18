<?php

namespace App\Http\Controllers;

use App\GetLovRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GetLovController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param GetLovRepository $repository
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, GetLovRepository $repository)
    {
        $result = Cache::remember(
            "lovs",
            200,
            function () use ($request, $repository) {
                return $repository->getLov($request);
            }
        );

        return $result;
    }
}
