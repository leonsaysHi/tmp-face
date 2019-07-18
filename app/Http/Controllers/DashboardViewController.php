<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardViewController extends Controller
{
    /**
     * @param Request $request
     * @return view
     */
    public function __invoke(Request $request)
    {
        return vue("dashboard");
    }
}
