<?php

namespace App\Repositories\Contracts;

interface DeviceRepositoryContract
{
    public function getTokens ($id_account_list);

    public function deleteMultiple ($device_token_list);

    public function upsert ($id_account, $device_token, $curr_time);
}
