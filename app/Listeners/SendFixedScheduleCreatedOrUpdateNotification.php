<?php

namespace App\Listeners;

use Exception;
use Carbon\Carbon;
use App\Models\Account;
use App\Helpers\Constants;
use App\Models\Notification;
use App\Models\FixedSchedule;
use App\Events\NotificationCreated;
use App\Events\FixedScheduleCreatedOrUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\Contracts\MailServiceContract;
use App\Repositories\Contracts\NotificationRepositoryContract;
use function App\Helpers\replaceStringKeys;

class SendFixedScheduleCreatedOrUpdateNotification implements ShouldQueue
{
    private MailServiceContract            $mailService;
    private NotificationRepositoryContract $notificationRepository;
    private FixedSchedule                  $fixedSchedule;
    private Account                        $loggingAccount;

    /**
     * @param MailServiceContract            $mailService
     * @param NotificationRepositoryContract $notificationRepository
     */
    public function __construct (MailServiceContract            $mailService,
                                 NotificationRepositoryContract $notificationRepository)
    {
        $this->mailService            = $mailService;
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * Handle the event.
     *
     * @param FixedScheduleCreatedOrUpdated $event
     *
     * @return void
     * @throws Exception
     */
    public function handle (FixedScheduleCreatedOrUpdated $event)
    {
        $this->fixedSchedule  = $event->getFixedSchedule();
        $this->loggingAccount = $event->getLoggingAccount();
        $this->__loadFixedScheduleRelationships();
        $this->__sendMailNotification();
        $this->__sendBroadcastNotification();
    }

    private function __loadFixedScheduleRelationships () : void
    {
        $this->fixedSchedule->load(['schedule:id,id_module_class',
                                    'schedule.moduleClass:id,name,id_teacher',
                                    'schedule.moduleClass.teacher.department:id,name',
                                    'schedule.moduleClass.teacher.department.teachers' => function ($query)
                                    {
                                        $query->where('is_head_of_department', '=', 1)
                                              ->with(['account:id,email,accountable_id']);
                                    },
                                    'schedule.moduleClass.teacher.account:id,email,accountable_id']);
    }

    private function __setUpData (array &$mailData, bool $isForHeadOfDepartment = false)
    {
        $teacher          = $this->fixedSchedule->schedule->moduleClass->teacher;
        $headOfDepartment = $this->fixedSchedule->schedule->moduleClass->teacher->department->teachers[0];
        $department       = $this->fixedSchedule->schedule->moduleClass->teacher->department;
        $moduleClass      = $this->fixedSchedule->schedule->moduleClass;

        $mailContent = replaceStringKeys($mailData['content'],
                                         [':head_of_department_name'   => $headOfDepartment->name,
                                          ':head_of_department_gender' => $headOfDepartment->is_female ? 'cô' : 'thầy',
                                          ':teacher_gender'            => $teacher->is_female ? 'cô' : 'thầy',
                                          ':teacher_name'              => $teacher->name,
                                          ':department_name'           => $department->name,]);

        $mailData = array_merge($mailData, [
            'recipient' => ($isForHeadOfDepartment ? $headOfDepartment : $teacher)->account->email,
            'data'      => [
                'fixed_schedule'    => $this->fixedSchedule->getOriginal(),
                'module_class_name' => $moduleClass->name,
                'fs_status'         => Constants::FIXED_SCHEDULE_STATUS,
                'status'            => $this->fixedSchedule->status,
                'content'           => $mailContent,
            ]
        ]);
    }

    private function __sendMailNotification ()
    {
        if (in_array($this->fixedSchedule->status,
                     [Constants::FIXED_SCHEDULE_STATUS['pending']['normal'],
                      Constants::FIXED_SCHEDULE_STATUS['pending']['soft']]))
        {
            $mailData = Constants::FIXED_SCHEDULE_MAIL_NOTIFICATION_FOR_HEAD_OF_DEPARTMENT[$this->fixedSchedule->status];
            $this->__setUpData($mailData, true);
            $this->mailService->sendFixedScheduleMailNotification($mailData);
        }

        $mailData = Constants::FIXED_SCHEDULE_MAIL_NOTIFICATION_FOR_TEACHER[$this->fixedSchedule->status];
        $this->__setUpData($mailData,);
        $this->mailService->sendFixedScheduleMailNotification($mailData);
    }

    /**
     * @throws Exception
     */
    private function __sendBroadcastNotification ()
    {
        if (isset(Constants::FIXED_SCHEDULE_BROADCAST_NOTIFICATION_FOR_TEACHER[$this->fixedSchedule->status]))
        {
            $notificationData = Constants::FIXED_SCHEDULE_BROADCAST_NOTIFICATION_FOR_TEACHER[$this->fixedSchedule->status];
            $this->__setUpDataAndCreateNotification($notificationData, 'teacher');
        }

        if (isset(Constants::FIXED_SCHEDULE_BROADCAST_NOTIFICATION_FOR_HEAD_OF_DEPARTMENT[$this->fixedSchedule->status]))
        {
            $notificationData = Constants::FIXED_SCHEDULE_BROADCAST_NOTIFICATION_FOR_HEAD_OF_DEPARTMENT[$this->fixedSchedule->status];
            $this->__setUpDataAndCreateNotification($notificationData, 'head_of_department');
        }

        if (isset(Constants::FIXED_SCHEDULE_BROADCAST_NOTIFICATION_FOR_ROOM_MANAGER[$this->fixedSchedule->status]))
        {
            $notificationData = Constants::FIXED_SCHEDULE_BROADCAST_NOTIFICATION_FOR_ROOM_MANAGER[$this->fixedSchedule->status];
            $this->__setUpDataAndCreateNotification($notificationData, 'room_manager');
        }
    }

    /**
     * @throws Exception
     */
    private function __setUpDataAndCreateNotification (array  $notificationData,
                                                       string $notificationTargetAudience)
    {
        $senderIdAccount   = $this->__getSenderIdAccount($notificationTargetAudience);
        $receiverIdAccount = $this->__getReceiverIdAccount($notificationTargetAudience);
        $now               = Carbon::now('+7')->format('Y-m-d H:i:s');
        sleep(1);

        $notification = [
            'type'       => Constants::NOTIFICATION_TYPE['accounts'],
            'data'       => [
                'content' => replaceStringKeys($notificationData['content'],
                                               [':teacher_name'      => $this->fixedSchedule->schedule->moduleClass->teacher->name,
                                                ':module_class_name' => $this->fixedSchedule->schedule->moduleClass->name]),
            ],
            'id_account' => $senderIdAccount,
            'action'     => config('app.front_end_url') .
                            "/schedule/change/{$this->fixedSchedule->id}",
            'created_at' => $now,
            'updated_at' => $now,
        ];

        $notification = $this->__createNotification($notification);
        $this->__createAccountNotification($notification, $receiverIdAccount);
        $this->__broadcastNotification($notification, $receiverIdAccount);
    }

    /**
     * @throws Exception
     */
    private function __getSenderIdAccount (string $notificationTargetAudience) : int
    {
        $status = $this->fixedSchedule->status;
        switch ($notificationTargetAudience)
        {
            case 'teacher':
                switch (true)
                {
                    case ($status == Constants::FIXED_SCHEDULE_STATUS['approve']['normal'] ||
                          $status == Constants::FIXED_SCHEDULE_STATUS['deny']['set_room']):
                        return $this->loggingAccount->id;

                    default:
                        return $this->fixedSchedule->schedule->moduleClass->teacher->department->teachers[0]->account->id;
                }

            case 'head_of_department':
                return $this->fixedSchedule->schedule->moduleClass->teacher->account->id;

            case 'room_manager':
                return $this->fixedSchedule->schedule->moduleClass->teacher->department->teachers[0]->account->id;

            default:
                throw new Exception(self::class);
        }
    }

    /**
     * @throws Exception
     */
    private function __getReceiverIdAccount (string $notificationTargetAudience) : int
    {
        switch ($notificationTargetAudience)
        {
            case 'teacher':
                return $this->fixedSchedule->schedule->moduleClass->teacher->account->id;

            case 'head_of_department':
                return $this->fixedSchedule->schedule->moduleClass->teacher->department->teachers[0]->account->id;

            case 'room_manager':
                return 2;

            default:
                throw new Exception(self::class);
        }
    }

    private function __createNotification (array $data) : Notification
    {
        return $this->notificationRepository->insertGetObject($data);
    }

    private function __createAccountNotification (Notification $notification,
                                                  int          $receiverIdAccount)
    {
        $notification->accounts()->attach(['id_account' => $receiverIdAccount]);
    }

    private function __broadcastNotification (Notification $notification, int $receiverIdAccount)
    {
        NotificationCreated::dispatch($notification, [$receiverIdAccount]);
    }
}
