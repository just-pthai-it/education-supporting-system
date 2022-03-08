<?php

namespace App\Services;

use App\Mail\FixedScheduleMailNotify;
use Illuminate\Support\Facades\Mail;

class MailService implements Contracts\MailServiceContract
{
    public function sendFixedScheduleMailNotify (array $package)
    {
        Mail::queue(new FixedScheduleMailNotify($package));
    }
}