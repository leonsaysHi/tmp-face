<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchSavedViewController extends Controller
{
    /**
     * @param Request $request
     * @return view
     */
    public function __invoke(Request $request)
    {
        return vue('saved-searches');
    }
}
