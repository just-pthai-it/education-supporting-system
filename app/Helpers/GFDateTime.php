<?php

namespace App\Helpers;

use DateTime;

class GFDateTime
{
    public static function calculateDateTime (string $dateTime, string $value, string $format)
    {
        return date($format, strtotime("{$dateTime}{$value}"));
    }

    public static function convertDateTime (string $date, string $from, string $to) : string
    {
        return DateTime::createFromFormat($from, $date)->format($to);
    }
}