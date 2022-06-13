<?php

namespace App\Helpers;

class GFDateTime
{
    public static function calculateDateTime (string $dateTime, string $value, string $format)
    {
        return date($format, strtotime("{$dateTime}{$value}"));
    }

    public static function convertDateFrom_dmy_To_Ymd (string $date)
    {
        return date('Y-m-d', strtotime(date('d-m-y', strtotime($date))));
    }
}