<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterProjectViewController extends Controller
{
    /**
     * @param Request $request
     * @param null $projectId
     * @return view
     */
    public function __invoke(Request $request, $projectId = null)
    {
        $data['projectId'] = $projectId;

        if (Auth::user()->user_type === 'Read Only') {
            return redirect('/dashboard');
        }

        return vue("register-project", compact('data'));
    }
}
