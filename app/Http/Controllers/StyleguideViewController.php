<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StyleguideViewController extends Controller
{
    /**
     * @param Request $request
     * @return view
     */
    public function __invoke(Request $request)
    {
        return vue("styleguide");
    }
}
