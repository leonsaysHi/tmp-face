<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class UserRepository extends SalesForceRepository
{
    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get()
    {
        $guid = Auth::user()->guid;
        return $this->post(config('endpoints.get_user'), compact('guid'));
    }

    /**
     * @param array $user
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function set($user = [])
    {
        $currentUser = Auth::user();

        $guid = $currentUser->guid;
        $user['guid'] = $currentUser->guid;

        return $this->post(config('endpoints.set_user'), compact('guid', 'user'));
    }

    /**
     * @param array $payload
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function search($payload = [])
    {
        $guid = Auth::user()->guid;
        $searchText = Arr::get($payload, 'searchText', '');
        $searchResource = Arr::get($payload, 'searchResource', false);
        return $this->post(config('endpoints.search_user'), compact('guid', 'searchText', 'searchResource'));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function verifyUser()
    {
        $response = $this->get();

        return is_array($response) && Arr::get($response, 'errorCode', 403) == "200";
    }
}
