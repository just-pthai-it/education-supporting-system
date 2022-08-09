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
    private FixedScheduleCreatedOrUpdated  $event;
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
        $this->event          = $event;
        $this->fixedSchedule  = $event->getFixedSchedule();
        $this->loggingAccount = $event->getLoggingAccount();
        $this->__loadFixedScheduleRelationships();

        $mailData = Constants::FIXED_SCHEDULE_MAIL_NOTIFICATION_FOR_TEACHER[$this->fixedSchedule->status];
        $this->_setUpData($mailData, $this->fixedSchedule);
        $this->__sendFixedScheduleMailNotification($mailData);
        $this->__sendMailNotificationToHeadOfDepartment($this->fixedSchedule);
        $this->__sendBroadcastFixedScheduleNotification();
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

    /**
     * @throws Exception
     */
    private function __sendMailNotificationToHeadOfDepartment (FixedSchedule $fixedSchedule)
    {
        if (!in_array($fixedSchedule->status,
                      [Constants::FIXED_SCHEDULE_STATUS['pending']['normal'],
                       Constants::FIXED_SCHEDULE_STATUS['pending']['soft']]))
        {
            return;
        }

        $teacher = $this->fixedSchedule->schedule->moduleClass->teacher->department->teachers[0];;
        $mailData = Constants::FIXED_SCHEDULE_MAIL_NOTIFICATION_FOR_HEAD_OF_DEPARTMENT[$fixedSchedule->status];;
        $mailContent = replaceStringKeys($mailData['content'],
                                         [':teacher_gender'  => $teacher->is_female ? 'cô' : 'thầy',
                                          ':teacher_name'    => $teacher->name,
                                          ':department_name' => $teacher->department->name,]);

        $this->__sendFixedScheduleMailNotification(['view'      => $mailData['view'],
                                                    'subject'   => $mailData['subject'],
                                                    'recipient' => $teacher->account->email,
                                                    'data'      => ['content' => $mailContent,],]);
    }

    private function _setUpData (array &$data, FixedSchedule $fixedSchedule)
    {
        $mailContent = replaceStringKeys($data['content'],
                                         [':teacher_gender'  => $fixedSchedule->schedule->moduleClass->teacher->is_female ? 'cô' : 'thầy',
                                          ':teacher_name'    => $fixedSchedule->schedule->moduleClass->teacher->name,
                                          ':department_name' => $fixedSchedule->schedule->moduleClass->teacher->department->name,]);

        $data = array_merge($data, [
            'recipient' => $fixedSchedule->schedule->moduleClass->teacher->account->email,
            'data'      => [
                'fixed_schedule'    => $fixedSchedule->getOriginal(),
                'module_class_name' => $fixedSchedule->schedule->moduleClass->name,
                'fs_status'         => Constants::FIXED_SCHEDULE_STATUS,
                'status'            => $fixedSchedule->status,
                'content'           => $mailContent,
            ]
        ]);
    }

    private function __sendFixedScheduleMailNotification ($data)
    {
        $this->mailService->sendFixedScheduleMailNotification($data);
    }

    /**
     * @throws Exception
     */
    private function __sendBroadcastFixedScheduleNotification ()
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
                'content' => $notificationData['content'],
            ],
            'id_account' => $senderIdAccount,
            'action'     => 'https://' . config('app.front_end_host') .
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

    public function __createNotification (array $data) : Notification
    {
        return $this->notificationRepository->insertGetObject($data);
    }

    public function __createAccountNotification (Notification $notification, int $receiverIdAccount)
    {
        $notification->accounts()->attach(['id_account' => $receiverIdAccount]);
    }

    public function __broadcastNotification (Notification $notification, int $receiverIdAccount)
    {
        NotificationCreated::dispatch($notification, [$receiverIdAccount]);
    }
}
