<?php

namespace App\Services\Contracts;

interface MailServiceContract
{
    public function sendFixedScheduleMailNotify (array $receivers, array $data);
}