<?php

namespace App\Http\Controllers;

use App\SAPRepository;
use Illuminate\Http\Request;

class SearchSAPController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param SAPRepository $repository
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __invoke(Request $request, SAPRepository $repository)
    {
        $input = $this->validate($request, [
            'searchText' => 'required|string'
        ]);

        return response()->json($repository->search($input));
    }
}
