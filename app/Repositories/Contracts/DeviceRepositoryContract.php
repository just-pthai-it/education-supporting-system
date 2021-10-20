<?php

namespace App\Repositories\Contracts;

interface DeviceRepositoryContract
{
    public function getDeviceTokens ($id_accounts);

    public function deleteMultiple ($device_tokens);
}
