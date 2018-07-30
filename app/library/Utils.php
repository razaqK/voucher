<?php

namespace Voucher\Library;

if (!function_exists('is_json')) {
    function is_json($json)
    {
        json_decode($json);

        return (json_last_error() == JSON_ERROR_NONE);
    }
}

class Utils
{

    public static function sanitizeInput($data)
    {
        if (is_object($data) && !empty($data)) {
            foreach ($data as $key => $value) {
                if (!is_object($value) && !is_array($value)) {
                    $data->$key = trim($value);
                }
            }

            return $data;
        }

        if (!empty($data)) {
            return trim($data);
        }

        return false;
    }

    public static function stripHtmlTags($params)
    {
        return array_map(function ($v) {
            return strtolower(strip_tags($v));
        }, $params);
    }

    /**
     * Generates Random alphanumeric short code
     *
     * @param int $length
     * @param $toLower
     * @return string
     */
    public static function generateRandomShortCode($length = 5, $toLower = false)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $toLower ? strtolower($randomString) : $randomString;
    }


    /**
     * Checks if JSON
     *
     * @param $json
     *
     * @return bool
     */
    public static function isJson($json)
    {
        return is_json($json);
    }

    /**
     * get offset from page and limit
     * @param $page
     * @param $limit
     * @return float|int
     */
    public static function getOffset($page, $limit)
    {
        return $page <= 0 ? 0 : ($page - 1) * $limit;
    }
}