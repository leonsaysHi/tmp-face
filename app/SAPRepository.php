<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class SAPRepository extends SalesForceRepository
{
    /**
     * @param array $payload
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function search($payload = [])
    {
        $searchText = Arr::get($payload, 'searchText');

        $guid = Auth::user()->guid;
        return $this->post(config('endpoints.search_sap'), compact('guid', 'searchText'));
    }

    /**
     * @param array $payload
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function attach($payload = [])
    {
        $projectSfdcId = Arr::get($payload, 'projectSfdcId');
        $sapSfdcIds = Arr::get($payload, 'sapSfdcIds');

        $guid = Auth::user()->guid;
        return $this->post(
            config('endpoints.attach_sap'),
            compact(
                'guid',
                'projectSfdcId',
                'sapSfdcIds'
            )
        );
    }

    /**
     * @param array $payload
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function detach($payload = [])
    {
        $sapSfdcIds = Arr::get($payload, 'sapSfdcIds');

        $guid = Auth::user()->guid;
        return $this->post(
            config('endpoints.detach_sap'),
            compact(
                'guid',
                'sapSfdcIds'
            )
        );
    }
}
