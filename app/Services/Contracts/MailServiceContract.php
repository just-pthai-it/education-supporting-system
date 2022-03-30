<?php

namespace App\Services\Contracts;

interface MailServiceContract
{
    public function sendFixedScheduleMailNotification (array $data);
}