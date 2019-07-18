<?php

namespace App\Http\Controllers;

use App\UserPreferencesRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class UserPreferencesController extends Controller
{

    protected $repository;

    /**
     * UserPreferencesController constructor.
     * @param UserPreferencesRepository $repository
     */
    public function __construct(UserPreferencesRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $platform
     * @return array
     */
    public function getPreferences($platform = '')
    {
        try {
            return response()->json($this->repository->getUserPreferences(Auth::user(), $platform));
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param Request $request
     * @param string $platform
     * @return array
     */
    public function setPreferences(Request $request, $platform = '')
    {
        try {
            $validatedInput = $this->validate($request, [
                'columnsSelected.*' => 'nullable',
                'perPage' => 'nullable',
                'sortBy' => 'nullable',
                'sortDesc' => 'nullable',
            ]);
            return response()
                ->json($this->repository->setUserPreferences($validatedInput, Auth::user(), $platform));
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json($e->getMessage(), $e->getCode());
        }
    }
}
