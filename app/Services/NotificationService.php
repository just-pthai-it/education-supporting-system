<?php


namespace App\Services;


use App\Repositories\Contracts\AccountRepositoryContract;
use App\Repositories\Contracts\DataVersionStudentRepositoryContract;
use App\Repositories\Contracts\DataVersionTeacherRepositoryContract;
use App\Repositories\Contracts\NotificationAccountRepositoryContract;
use App\Repositories\Contracts\NotificationRepositoryContract;
use App\Repositories\Contracts\ParticipateRepositoryContract;
use App\Repositories\Contracts\StudentRepositoryContract;
use App\Helpers\SharedFunctions;
use App\Services\Contracts\NotificationServiceContract;

class NotificationService implements NotificationServiceContract
{
    private NotificationAccountRepositoryContract $notificationAccountDepository;
    private DataVersionStudentRepositoryContract $dataVersionStudentDepository;
    private DataVersionTeacherRepositoryContract $dataVersionTeacherRepository;
    private NotificationRepositoryContract $notificationDepository;
    private ParticipateRepositoryContract $participateDepository;
    private AccountRepositoryContract $accountDepository;
    private StudentRepositoryContract $studentDepository;

    /**
     * @param NotificationAccountRepositoryContract $notificationAccountDepository
     * @param DataVersionStudentRepositoryContract $dataVersionStudentDepository
     * @param DataVersionTeacherRepositoryContract $dataVersionTeacherRepository
     * @param NotificationRepositoryContract $notificationDepository
     * @param ParticipateRepositoryContract $participateDepository
     * @param AccountRepositoryContract $accountDepository
     * @param StudentRepositoryContract $studentDepository
     */
    public function __construct (NotificationAccountRepositoryContract $notificationAccountDepository,
                                 DataVersionStudentRepositoryContract  $dataVersionStudentDepository,
                                 DataVersionTeacherRepositoryContract  $dataVersionTeacherRepository,
                                 NotificationRepositoryContract        $notificationDepository,
                                 ParticipateRepositoryContract         $participateDepository,
                                 AccountRepositoryContract             $accountDepository,
                                 StudentRepositoryContract             $studentDepository)
    {
        $this->notificationAccountDepository = $notificationAccountDepository;
        $this->dataVersionStudentDepository  = $dataVersionStudentDepository;
        $this->dataVersionTeacherRepository  = $dataVersionTeacherRepository;
        $this->notificationDepository        = $notificationDepository;
        $this->participateDepository         = $participateDepository;
        $this->accountDepository             = $accountDepository;
        $this->studentDepository             = $studentDepository;
    }

    public function pushFCNotification ($notification, $class_list) : array
    {
        $id_student_list = $this->_getIDStudentsBFC($class_list);
        $id_account_list = $this->_sharedFunctions($notification, $id_student_list);

        return $id_account_list;
    }

    private function _getIDStudentsBFC ($class_list)
    {
        return $this->studentDepository->getIDStudents($class_list);
    }

    public function pushMCNotification ($notification, $class_list) : array
    {
        $id_student_list = $this->_getIDStudentsBMC($class_list);
        $id_account_list = $this->_sharedFunctions($notification, $id_student_list);
        return $id_account_list;
    }

    private function _getIDStudentsBMC ($class_list)
    {
        return $this->participateDepository->getIDStudents($class_list);
    }

    private function _sharedFunctions ($notification, $id_student_list) : array
    {
        $id_account_list = $this->_getIDAccounts(SharedFunctions::formatArray($id_student_list, 'id_student'));
        $id_notification = $this->_createNotification($notification);
        $this->_createNotificationAccount($id_account_list, $id_notification);
        $this->_updateNotificationDataVersionStudent($id_student_list);

        return $id_account_list;
    }

    private function _getIDAccounts ($id_student_list) : array
    {
        return empty($id_student_list) ? [] : $this->accountDepository->getIDAccounts($id_student_list);
    }

    private function _createNotification ($notification)
    {
        $notification = $this->_setUpNotification($notification);
        return $this->notificationDepository->insertGetID($notification);
    }

    private function _setUpNotification ($notification) : array
    {
        return [
            'title'       => SharedFunctions::formatString($notification['title']),
            'content'     => SharedFunctions::formatString($notification['content']),
            'type'        => $notification['type'],
            'id_sender'   => $notification['id_sender'],
            'time_create' => SharedFunctions::getDateTimeNow(),
            'time_start'  => $notification['time_start'],
            'time_end'    => $notification['time_end']
        ];
    }

    private function _createNotificationAccount ($id_account_list, $id_notification)
    {
        if (empty($id_account_list))
        {
            return;
        }

        $data = $this->_setUpNotificationAccount($id_account_list, $id_notification);
        $this->notificationAccountDepository->insertMultiple($data);
    }

    public function _setUpNotificationAccount ($id_account_list, $id_notification) : array
    {
        $arr = [];
        foreach ($id_account_list as $id_account)
        {
            $arr[] = [
                'id_notification' => $id_notification,
                'id_account'      => $id_account
            ];
        }
        return $arr;
    }

    public function setDelete ($id_sender, $id_notifications)
    {
        $this->notificationDepository->setDelete($id_sender, $id_notifications);
        $id_student_list = $this->notificationAccountDepository->getIDAccounts($id_notifications);
        $this->_updateNotificationDataVersionStudent($id_student_list);
    }

    private function _updateNotificationDataVersionStudent ($id_student_list)
    {
        $this->dataVersionStudentDepository->updateMultiple($id_student_list, 'notification');
    }

    public function getReceivedNotifications ($id_account, $offset) : array
    {
        $id_notifications = $this->notificationAccountDepository->getIDNotifications($id_account, $offset);
        $data = $this->notificationDepository->getNotifications2($id_notifications);
        return $this->_formatNotificationResponse($data);
    }

    private function _formatNotificationResponse ($data) : array
    {
        $response = [];
        foreach ($data as $part)
        {
            foreach ($part as $notification)
            {
                $response['sender'][] = [
                    'id_sender'   => $notification['id_sender'],
                    'sender_name' => $notification['sender_name'],
                    'permission'  => $notification['permission']
                ];

                unset($notification['sender_name']);
                unset($notification['permission']);

                $response['notification'][] = $notification;
            }
        }

        if (isset($response['sender']))
        {
            $response['sender'] = array_values(array_unique($response['sender'], SORT_REGULAR));
        }

        return $response;
    }

    public function getSentNotifications ($id_sender, $offset)
    {
        return $this->notificationDepository->getNotifications1($id_sender, $offset);
    }
}
