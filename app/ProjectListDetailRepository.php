<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ProjectListDetailRepository extends SalesForceRepository
{
    /**
     * @param array $payload
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getProjectListDetails($payload = [])
    {
        $projectSfdcIds = Arr::get($payload, 'projectSfdcIds', []);
        $guid = Auth::user()->guid;
        return $this->post(
            config('endpoints.get_project_list_detail'),
            compact('guid', 'projectSfdcIds')
        );
    }
}
