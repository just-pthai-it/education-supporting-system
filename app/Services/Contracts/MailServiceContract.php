<?php

namespace App\Services\Contracts;

interface MailServiceContract
{
    public function sendFixedScheduleMailNotify (array $package);
}