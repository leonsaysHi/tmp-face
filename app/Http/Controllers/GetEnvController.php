<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GetEnvController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return env('APP_ENV');
    }
}
