<?php

namespace App\Helpers;

class GFArray
{
    public static function onlyKeys (array $array, array $columns) : array
    {
        $data = [];
        foreach ($columns as $key => $column)
        {
            if (key_exists($key, $array) && is_string($key))
            {
                $data[$column] = $array[$key];
            }
            else
            {
                $data[$column] = $array[$column];
            }
        }
        return $data;
    }
}