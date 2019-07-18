<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CEPRepository extends SalesForceRepository
{

    /**
     * @param array $payload
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($payload = [])
    {
        $projectSfdcId = Arr::get($payload, 'projectSfdcId');

        $guid = Auth::user()->guid;
        return $this->post(
            config('endpoints.get_cep'),
            compact(
                'guid',
                'projectSfdcId'
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
        $cepSfdcIds = Arr::get($payload, 'cepSfdcIds');

        $guid = Auth::user()->guid;
        return $this->post(
            config('endpoints.detach_cep'),
            compact(
                'guid',
                'cepSfdcIds'
            )
        );
    }

    /**
     * @param array $payload
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
   
    public function createManual($payload = [])
    {
        $projectSfdcId = Arr::get($payload, 'projectSfdcId', '');
        $CEPId = Arr::get($payload, 'CEPId', '');
        $CEPFolderId = Arr::get($payload, 'CEPFolderId', '');
        $CEPStatus = Arr::get($payload, 'CEPStatus', '');

        $guid = Auth::user()->guid;
        return $this->post(
            config('endpoints.create_cep'),
            compact(
                'guid',
                'projectSfdcId',
                'CEPId',
                'CEPFolderId',
                'CEPStatus'
            )
        );
    }
}
