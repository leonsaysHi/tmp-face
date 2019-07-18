<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileViewController extends Controller
{
    /**
     * @param Request $request
     * @return view
     */
    public function __invoke(Request $request)
    {
        return vue("profile");
    }
}
