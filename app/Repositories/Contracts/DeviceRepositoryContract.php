<?php

namespace App\Repositories\Contracts;

interface DeviceRepositoryContract
{
    public function getDeviceTokens ($id_account_list);

    public function deleteMultiple ($device_token_list);
}
