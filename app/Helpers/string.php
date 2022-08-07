<?php

namespace App\Helpers;

function replaceStringKeys (string $str, array $keyValue) : string
{
    foreach ($keyValue as $key => $value)
    {
        $str = str_replace($key, $value, $str);
    }

    return $str;
}