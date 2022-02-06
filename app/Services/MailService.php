<?php

namespace App\Services;

use App\Mail\FixedScheduleMailNotify;
use Illuminate\Support\Facades\Mail;

class MailService implements Contracts\MailServiceContract
{
    public function sendFixedScheduleMailNotify (array $receivers, array $data)
    {
        foreach ($receivers as $receiver)
        {
            Mail::to($receiver)->queue(new FixedScheduleMailNotify($data));
        }
    }
}