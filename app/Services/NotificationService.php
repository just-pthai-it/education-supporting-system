<?php

namespace App\Services;

use App\Repositories\Contracts\AccountRepositoryContract;
use App\Repositories\Contracts\DataVersionStudentRepositoryContract;
use App\Repositories\Contracts\DataVersionTeacherRepositoryContract;
use App\Repositories\Contracts\NotificationRepositoryContract;
use App\Repositories\Contracts\StudentRepositoryContract;
use App\Helpers\SharedFunctions;
use App\Services\Contracts\NotificationServiceContract;

class NotificationService implements Contracts\NotificationServiceContract
{
    private DataVersionStudentRepositoryContract $dataVersionStudentDepository;
    private DataVersionTeacherRepositoryContract $dataVersionTeacherRepository;
    private NotificationRepositoryContract $notificationDepository;
    private AccountRepositoryContract $accountDepository;
    private StudentRepositoryContract $studentDepository;

    /**
     * @param DataVersionStudentRepositoryContract $dataVersionStudentDepository
     * @param DataVersionTeacherRepositoryContract $dataVersionTeacherRepository
     * @param NotificationRepositoryContract       $notificationDepository
     * @param AccountRepositoryContract            $accountDepository
     * @param StudentRepositoryContract            $studentDepository
     */
    public function __construct (DataVersionStudentRepositoryContract $dataVersionStudentDepository,
                                 DataVersionTeacherRepositoryContract $dataVersionTeacherRepository,
                                 NotificationRepositoryContract       $notificationDepository,
                                 AccountRepositoryContract            $accountDepository,
                                 StudentRepositoryContract            $studentDepository)
    {
        $this->dataVersionStudentDepository = $dataVersionStudentDepository;
        $this->dataVersionTeacherRepository = $dataVersionTeacherRepository;
        $this->notificationDepository       = $notificationDepository;
        $this->accountDepository            = $accountDepository;
        $this->studentDepository            = $studentDepository;
    }

    public function pushFCNotification ($notification, $classes) : array
    {
        $id_students = $this->_getIDStudentsBFC($classes);
        $id_accounts = $this->_sharedFunctions($notification, $id_students);

        return $id_accounts;
    }

    private function _getIDStudentsBFC ($classs)
    {
        return $this->studentDepository->getIDStudents1($classs);
    }

    public function pushMCNotification ($notification, $classes) : array
    {
        $id_students = $this->_getIDStudentsBMC($classes);
        $id_accounts = $this->_sharedFunctions($notification, $id_students);
        return $id_accounts;
    }

    private function _getIDStudentsBMC ($classes)
    {
        return $this->studentDepository->getIDStudents2($classes);
    }

    private function _sharedFunctions ($notification, $id_students) : array
    {
        $id_accounts     = $this->_getIDAccounts(SharedFunctions::formatArray($id_students,
                                                                              'id_student'));
        $id_notification = $this->_createNotification($notification);
        $this->_createNotificationAccount($id_accounts, $id_notification);
        $this->_updateNotificationDataVersionStudent($id_students);

        return $id_accounts;
    }

    private function _getIDAccounts ($id_students) : array
    {
        return empty($id_students) ? [] : $this->accountDepository->getIDAccounts1($id_students);
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

    private function _createNotificationAccount ($id_accounts, $id_notification)
    {
        if (empty($id_accounts))
        {
            return;
        }

        $this->notificationDepository->insertPivotMultiple($id_notification, $id_accounts);
    }

    public function setDelete ($id_sender, $id_notifications)
    {
        $this->notificationDepository->update($id_sender, $id_notifications);
        $id_accounts = $this->accountDepository->getIDAccounts2($id_notifications);
        $id_students = $this->_getIDStudentsByIDAccounts($id_accounts);
        $this->_updateNotificationDataVersionStudent($id_students);
    }

    private function _getIDStudentsByIDAccounts ($id_accounts)
    {
        return $this->studentDepository->getIDStudents3($id_accounts);
    }

    private function _updateNotificationDataVersionStudent ($id_students)
    {
        $this->dataVersionStudentDepository->updateMultiple($id_students, 'notification');
    }

    public function getReceivedNotifications ($id_account, $offset) : array
    {
        $id_notifications = $this->notificationDepository->getIDNotifications($id_account, $offset);
        $data             = $this->notificationDepository->getNotifications2($id_notifications);
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
                ];
                unset($notification['sender_name']);

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
