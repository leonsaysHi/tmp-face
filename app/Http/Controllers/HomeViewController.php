<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeViewController extends Controller
{
    /**
     * @param Request $request
     * @return view
     */
    public function __invoke(Request $request)
    {
        return vue("home");
    }
}
