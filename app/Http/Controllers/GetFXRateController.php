<?php

namespace App\Http\Controllers;

use App\GetFXRateRepository;

class GetFXRateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param GetFXRateRepository $repository
     * @return \Illuminate\Http\Response
     */
    public function __invoke(GetFXRateRepository $repository)
    {
        return response()->json($repository->getFXRate());
    }
}
