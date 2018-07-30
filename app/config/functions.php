<?php

if (!function_exists('is_json')) {
    function is_json($json)
    {
        json_decode($json);

        return (json_last_error() == JSON_ERROR_NONE);
    }
}