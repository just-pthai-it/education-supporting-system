<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;

function calculateDatetime (string $datetime, string $value, string $format)
{
    return date($format, strtotime("{$datetime}{$value}"));
}

 function convertDateTime (string $date, string $from, string $to) : string
{
    return Carbon::createFromFormat($from, $date)->format($to);
}