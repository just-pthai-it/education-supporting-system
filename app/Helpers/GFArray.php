<?php

namespace App\Helpers;

class GFArray
{
    public static function onlyKeys (array $array, array $keys) : array
    {
        $data = [];
        foreach ($keys as $key => $value)
        {
            if (key_exists($key, $array) && is_string($key))
            {
                $data[$value] = $array[$key];
            }
            else
            {
                $data[$value] = $array[$value];
            }
        }

        return $data;
    }
}