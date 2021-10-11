<?php


namespace App\Services;


use App\Repositories\Contracts\DeviceRepositoryContract;
use App\Services\Contracts\DeviceServiceContract;

class DeviceService implements DeviceServiceContract
{
    private DeviceRepositoryContract $deviceDepository;

    /**
     * DeviceService constructor.
     * @param DeviceRepositoryContract $deviceDepository
     */
    public function __construct (DeviceRepositoryContract $deviceDepository)
    {
        $this->deviceDepository = $deviceDepository;
    }


    public function upsert ($id_account, $device_token)
    {
        $curr_time = $this->_getCurrentTime();
        $this->deviceDepository->upsert($id_account, $device_token, $curr_time);
    }

    private function _getCurrentTime ()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        return date('Y-m-d H:i:s');
    }
}
