<?php

namespace App\Repositories;

use App\Models\Device;
use Illuminate\Support\Facades\DB;

class DeviceRepository implements Contracts\DeviceRepositoryContract
{
    public function getDeviceTokens ($id_accounts) : array
    {
        $this->_createTemporaryTable($id_accounts);
        return DB::table(Device::table_as)
                 ->join('temp2', 'd.id_account', 'temp2.id_account')
                 ->pluck('device_token')->toArray();
    }

    public function deleteMultiple ($device_tokens)
    {
        Device::whereIn('device_token', $device_tokens)->delete();
    }

    private function _createTemporaryTable ($id_accounts)
    {
        $sql_query =
            'CREATE TEMPORARY TABLE temp2 (
                  id_account mediumint unsigned NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

        DB::unprepared($sql_query);
        DB::table('temp2')->insert($id_accounts);
    }
}
