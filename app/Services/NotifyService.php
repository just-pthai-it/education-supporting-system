<?php

namespace App\Services;

use App\BusinessClasses\FirebaseCloudMessaging;
use App\Repositories\Contracts\DeviceRepositoryContract;
use App\Helpers\SharedFunctions;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;

class NotifyService implements Contracts\NotifyServiceContract
{
    private DeviceRepositoryContract $deviceDepository;
    private FirebaseCloudMessaging $fcm;

    /**
     * @param DeviceRepositoryContract $depositoryContract
     * @param FirebaseCloudMessaging   $fcm
     */
    public function __construct (DeviceRepositoryContract $depositoryContract,
                                 FirebaseCloudMessaging   $fcm)
    {
        $this->deviceDepository = $depositoryContract;
        $this->fcm              = $fcm;
    }

    /**
     * @throws MessagingException
     * @throws FirebaseException
     */
    public function sendNotification ($notification, $id_accounts)
    {
        $device_tokens         = $this->_getDeviceTokens($id_accounts);
        $invalid_device_tokens = $this->_send($notification, $device_tokens);
        $this->_deleteInvalidTokens($invalid_device_tokens);
    }

    private function _getDeviceTokens ($id_accounts) : array
    {
        return empty($id_accounts) ?
            [] : $this->deviceDepository->getDeviceTokens(SharedFunctions::formatArray($id_accounts,
                                                                                       'id_account'));
    }

    /**
     * @throws MessagingException
     * @throws FirebaseException
     */
    private function _send ($notification, $device_tokens) : array
    {
        $this->fcm->setUpData($notification, $device_tokens);
        return $this->fcm->send();
    }

    private function _deleteInvalidTokens ($device_tokens)
    {
        if (empty($device_tokens))
        {
            return;
        }

        $this->deviceDepository->deleteMultiple($device_tokens);
    }
}
