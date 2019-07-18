<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Return the type info of currently logged in user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function __invoke()
    {
        return response()->json(['data' => Auth::user()->user_type]);
    }
}
