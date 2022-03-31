<?php

namespace App\Services;

use App\Mail\FixedScheduleMailNotify;
use Illuminate\Support\Facades\Mail;

class MailService implements Contracts\MailServiceContract
{
    public function sendFixedScheduleMailNotification (array $data)
    {
        Mail::queue(new FixedScheduleMailNotify($data));
    }
}