<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectDetailsViewController extends Controller
{
    /**
     * @param Request $request
     * @return view
     */
    public function __invoke(Request $request, $projectId)
    {
        $data['projectId'] = $projectId;
        return vue("project-details", compact('data'));
    }
}
