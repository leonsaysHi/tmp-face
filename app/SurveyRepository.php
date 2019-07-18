<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class SurveyRepository extends SalesForceRepository
{
    /**
     * @param array $payload
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function setResults($payload = [])
    {
        $projectSfdcId = Arr::get($payload, 'projectSfdcId', null);
        $userSfdcId = Arr::get($payload, 'userSfdcId', null);
        $results = Arr::get($payload, 'results', []);
        // $rate = Arr::get($payload, 'rate', null);
        // $comments = Arr::get($payload, 'comments', null);
        $guid = Auth::user()->guid;

        return $this->post(
            config('endpoints.set_result'),
            compact(
                'guid',
                'projectSfdcId',
                'userSfdcId',
                'results'
                // 'questionSfdcId',
                // 'rate',
                // 'comments'
            )
        );
    }

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getQuestion()
    {
        $guid = Auth::user()->guid;

        return $this->post(config('endpoints.get_question'), compact('guid'));
    }
}
