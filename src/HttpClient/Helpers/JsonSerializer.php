<?php

namespace Sens\HttpClient\Helpers;

class JsonSerializer
{
    /**
     * Encode the given string data to json.
     *
     * @param  array  $data
     * @return string
     */
    public static function encode(array $data)
    {
        return json_encode($data);
    }

    /**
     * Decode the given json string to array.
     *
     * @param  string  $data
     * @return array
     */
    public static function decode(string $data)
    {
        return json_decode($data);
    }
}
