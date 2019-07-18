<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

if (!function_exists('vue')) {
    function vue($view, $data = [])
    {
        $data = json_encode($data);

        return view('vue', compact('data', 'view'));
    }
}


if (!function_exists('array_filter_key')) {
    /**
     * Filtering a array by its keys using a callback.
     *
     * @param $array array The array to filter
     * @param $callback Callback The filter callback, that will get the key as first argument.
     *
     * @return array The remaining key => value combinations from $array.
     */
    function array_filter_key(array $array, $callback)
    {
        $matchedKeys = array_filter(array_keys($array), $callback);
        return array_intersect_key($array, array_flip($matchedKeys));
    }
}


if (!function_exists('cast_to_bool')) {
    /**
     * Cast a truthy/falsy value to boolean.
     *
     * @param $value
     * @return bool
     */
    function cast_to_bool($value)
    {
        if ($value === 'true' || $value === 'TRUE' || $value === 1 || $value === true) {
            $value = true;
        }

        if ($value === 'false' || $value === 'FALSE' || $value === 0 || $value === false) {
            $value = false;
        }

        return $value;
    }
}


if (!function_exists('log_sf_error')) {
    /**
     * Log a sales force error to its dedicated log file with given info.
     *
     * @param $endPoint
     * @param $originalError
     * @param $payload
     * @param $uniqueRefId
     * @return void
     */

    function log_sf_error($endPoint, $originalError, $payload, $uniqueRefId)
    {
        $user = Auth::user();
        $payload = json_encode($payload);

        $originalError = is_array($originalError) ? implode(',', $originalError) : $originalError;

        Log::channel('sales_force_error_log')
            ->debug(
                '<h5>Unique Reference ID: ' . $uniqueRefId . '</h5><hr>',
                ["<span style='color: red'><b>Endpoint:</b></span> <p>{$endPoint}</p> <br> <span style='color: red'> <b>User Info:</b></span> <p><b>Name</b>: {$user->name} | <b>Email</b>: {$user->email} | <b>Guid</b>: {$user->guid}</p> <div style='border: 1px solid gray; padding: 10px'><span style='color: red'> <b>Original Error Body:</b></span> <br>{$originalError}</div> <div style='border: 1px solid gray; padding: 10px'><span style='color: red'> <b>User's Payload:</b></span> <br>{$payload}</div>"]
            );
    }
}

if (!function_exists('normalize_string')) {
    /**
     * Sanitizes the string to be a compatible filename amongst operation systems.
     *
     * @param string $str
     * @return void
     */

    function normalize_string($str = '')
    {
        $str = strip_tags($str);
        $str = preg_replace('/[\r\n\t ]+/', ' ', $str);
        $str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
        $str = strtolower($str);
        $str = html_entity_decode($str, ENT_QUOTES, "utf-8");
        $str = htmlentities($str, ENT_QUOTES, "utf-8");
        $str = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
        $str = str_replace(' ', '-', $str);
        $str = rawurlencode($str);
        $str = str_replace('%', '-', $str);
        return $str;
    }
}
