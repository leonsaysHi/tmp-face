<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class APITestController extends Controller
{
  /**
   * @param Request $request
   * @return view
   */
    public function __invoke(Request $request)
    {
        try {
            $devusers = [
            "27212996",
            "21596364",
            "21374722",
            "23705966",
            "20713942",
            "25640036",
            "24330035",
            "20400318",
            "16122033",
            "28638999",
            "24641735",
            "17379254",
            "21234796",
            "27801538",
            "24132093",
            "16009387",
            "22015327"
            ];

            $user = Auth::user();
            if (in_array($user->guid, $devusers) || in_array($user->viewas_guid, $devusers)) {
                return view("apiviewer");
            } else {
                return redirect()
                ->intended(config('saml.redirect', '/'));
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->withErrors("Sorry error with page");
        }
    }

    public function setTester(Request $request)
    {
        $user = Auth::user();
        $mockusers = [
            "27212996",
            "21596364",
            "21374722",
            "23705966",
            "20713942",
            "25640036",
            "24330035",
            "20400318",
            "16122033",
            "28638999",
            "24641735",
            "17379254",
            "21234796",
            "27801538",
            "24132093",
            "16009387",
            "22015327"
        ];
        if ($request['status'] == "on") {
            if (in_array($user->guid, $mockusers) && $user->viewas_guid == null) {
                $user->role = 'tester';
                $user->user_type = "Manual";

                if (isset($request['type'])) {
                    $user->user_type = $request['type'];
                }

                $user->save();

                return redirect()
                ->intended(config('saml.redirect', '/masquerade'))
                ->with('message', 'You are now logged in');
            }
        }

        if ($request['status'] == "off") {
            $user->user_type = "Manual";
            $user->save();
            return redirect()
            ->intended(config('saml.redirect', '/end-masquerade'))
            ->with('message', 'You are now logged in');
        }

        return redirect()
        ->intended(config('saml.redirect', '/'))
        ->with('message', 'You are now logged in');
    }

    public function masquerade(Request $request)
    {
        $user = Auth::user();
        $user->viewas_guid = $request['guid'];
        $user->save();
        return redirect()
        ->intended(config('saml.redirect', '/'))
        ->with('message', 'You are now Masquerading');
    }

    public function endMasquerade()
    {
        $user = Auth::user();
        $user->viewas_guid = null;
        $user->role = null;
        $user->user_type = "Manual";
        $user->save();
        return redirect()
        ->intended(config('saml.redirect', '/'))
        ->with('message', 'Ended the Masquerade');
    }

    /**
     * List all cached data.
     *
     * @return array
     */
    public function getCachedData()
    {
        $storage = Cache::getStore();
        $filesystem = $storage->getFilesystem();
        $dir = (Cache::getDirectory());
        $keys = [];
        foreach ($filesystem->allFiles($dir) as $file1) {
            if (is_dir($file1->getPath())) {
                foreach ($filesystem->allFiles($file1->getPath()) as $file2) {
                    $keys = array_merge($keys, [$file2->getRealpath() =>
                        unserialize(substr(\File::get($file2->getRealpath()), 10))]);
                }
            }
        }
        return $keys;
    }

    /**
     * Remove a cached data from the storage by given key value.
     *
     * @param $cacheName
     * @return \Illuminate\Http\JsonResponse
     */
    public function unCache($cacheName)
    {
        if ($cacheName === "all") {
            //todo: exclude laravel token and userId
            Cache::flush();
            return response()->json(["message" => "Successfully uncached everything" ], 200);
        }

        if (Cache::has($cacheName)) {
            Cache::forget($cacheName);
            return response()->json(["message" => "Successfully uncached ${cacheName}" ], 200);
        }
        return response()->json(["message" => "No cache key found with given key value: ${cacheName}" ], 404);
    }
}
