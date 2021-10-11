<?php

namespace App\Services\Contracts;

interface DeviceServiceContract
{
    public function upsert ($id_account, $device_token);
}
