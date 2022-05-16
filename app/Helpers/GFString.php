<?php

namespace App\Helpers;

class GFString
{
    public static function removeExtraSpace (string $str) : string
    {
        return trim(preg_replace('/[ ]+/', ' ', $str));
    }

    public static function removeAllSpace (string $str) : string
    {
        return trim(preg_replace('/[ ]+/', '', $str));
    }

    public static function removeAllEndOfLine (string $str)
    {
        return preg_replace('/[\n]+/', '', $str);
    }
}