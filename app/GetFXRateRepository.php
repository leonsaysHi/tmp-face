<?php

namespace App;

use Illuminate\Support\Facades\Auth;

class GetFXRateRepository extends SalesForceRepository
{
    public function getFXRate()
    {
        $guid = Auth::user()->guid;
        return $this->post(config('endpoints.get_fx_rate'), compact('guid'));
    }
}
