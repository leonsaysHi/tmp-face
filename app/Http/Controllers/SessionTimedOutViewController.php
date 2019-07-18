<?php

namespace App\Http\Controllers;

class SessionTimedOutViewController extends Controller
{
    public function __invoke()
    {
        return vue('session-timed-out');
    }
}
