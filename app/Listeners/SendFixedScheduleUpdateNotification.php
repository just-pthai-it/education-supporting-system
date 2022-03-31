<?php

namespace App\Listeners;

use Exception;
use App\Helpers\GData;
use App\Models\Teacher;
use App\Models\FixedSchedule;
use App\Events\FixedScheduleUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\Contracts\MailServiceContract;

class SendFixedScheduleUpdateNotification implements ShouldQueue
{
    private MailServiceContract $mailService;

    /**
     * @param MailServiceContract $mailService
     */
    public function __construct (MailServiceContract $mailService)
    {
        $this->mailService = $mailService;
    }

    /**
     * Handle the event.
     *
     * @param FixedScheduleUpdated $event
     *
     * @return void
     * @throws Exception
     */
    public function handle (FixedScheduleUpdated $event)
    {
        $fixedSchedule = $event->getFixedSchedule();
        $this->_loadFixedScheduleRelationships($fixedSchedule);
        $mailData = $this->_getBasicMailData($fixedSchedule->status);
        $this->_setUpData($mailData, $fixedSchedule);
        $this->_sendMailNotification($mailData);
        $this->_sendMailNotificationToHeadOfDepartment($fixedSchedule);
    }

    private function _loadFixedScheduleRelationships (FixedSchedule &$fixedSchedule)
    {
        $fixedSchedule->load(['schedule:id,id_module_class',
                              'schedule.moduleClass:id,name,id_teacher',
                              'schedule.moduleClass.teacher:id,name,is_female,id_department',
                              'schedule.moduleClass.teacher.account:accountable_id,email']);
    }

    /**
     * @throws Exception
     */
    private function _sendMailNotificationToHeadOfDepartment (FixedSchedule $fixedSchedule)
    {
        if ($fixedSchedule->status != 0)
        {
            return;
        }

        $teacher  = $this->_readHeadOfDepartment($fixedSchedule->schedule->moduleClass->teacher->id_department);
        $mailData = $this->_getBasicMailData($fixedSchedule->status, true);
        $this->_sendMailNotification(array_merge($mailData, [
            'recipient'  => $teacher->account->email,
            'teacher'    => $teacher->getOriginal(),
            'department' => $teacher->department->getOriginal(),
        ]));
    }

    private function _readHeadOfDepartment (string $idDepartment)
    {
        return Teacher::where('id_department', '=', $idDepartment)
                      ->where('is_head_of_department', '=', 1)
                      ->with(['department:id,name', 'account:email,accountable_type,accountable_id'])
                      ->first(['id', 'name', 'is_female', 'id_department']);
    }


    /**
     * @throws Exception
     */
    private function _getBasicMailData (int $status, bool $isForHeadOfDepartment = false) : array
    {
        switch ($status)
        {
            case -3:
                return GData::$mail_data['change_schedule_request']['cancel'];
            case -2:
                return GData::$mail_data['change_schedule_request']['deny_room'];
            case -1:
                return GData::$mail_data['change_schedule_request']['deny'];
            case 0:
                if (!$isForHeadOfDepartment)
                {
                    return GData::$mail_data['change_schedule_request']['confirm'];
                }
                else
                {
                    return GData::$mail_data['change_schedule_request']['notify_head_of_department'];
                }
            case 1:
                return GData::$mail_data['change_schedule_request']['accept'];
            case 2:
                return GData::$mail_data['change_schedule_request']['accept_room'];
            case 3:
                return GData::$mail_data['change_schedule_request']['accept_straight'];
            default:
                throw new Exception('send mail fixed schedule');
        }
    }

    private function _setUpData (array &$data, FixedSchedule $fixedSchedule)
    {
        $data = array_merge($data, [
            'recipient'      => $fixedSchedule->schedule->moduleClass->teacher->account->email,
            'fixed_schedule' => $fixedSchedule->getOriginal(),
            'module_class'   => $fixedSchedule->schedule->moduleClass->getOriginal(),
            'teacher'        => $fixedSchedule->schedule->moduleClass->teacher->getOriginal(),
        ]);
    }

    private function _sendMailNotification ($data)
    {
        $this->mailService->sendFixedScheduleMailNotification($data);
    }
}
