<?php

namespace App;

use Illuminate\Support\Facades\Auth;

class GetLinkRepository extends SalesForceRepository
{
    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getLink()
    {
        $guid = Auth::user()->guid;
        return $this->post(config('endpoints.get_link'), compact('guid'));
    }
}
