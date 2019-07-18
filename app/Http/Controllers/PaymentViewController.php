<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentViewController extends Controller
{
    /**
     * @param Request $request
     * @return view
     */
    public function __invoke(Request $request)
    {
        return vue("payment");
    }
}
