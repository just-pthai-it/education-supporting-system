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
        if (!in_array($fixedSchedule->status, [GData::$fsStatusCode['pending']['normal'],
                                               GData::$fsStatusCode['pending']['soft']]))
        {
            return;
        }

        $teacher  = $this->_readHeadOfDepartment($fixedSchedule->schedule->moduleClass->teacher->id_department);
        $mailData = $this->_getBasicMailDataForHeadOfDepartment();
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
    private function _getBasicMailData (int $status) : array
    {
        switch ($status)
        {
            case GData::$fsStatusCode['cancel']['normal']:
                return GData::$mail_data['change_schedule_request']['cancel'];

            case GData::$fsStatusCode['deny']['set_room']:
                return GData::$mail_data['change_schedule_request']['deny_set_room'];

            case GData::$fsStatusCode['deny']['accept']:
                return GData::$mail_data['change_schedule_request']['deny_accept'];

            case GData::$fsStatusCode['pending']['normal']:
            case GData::$fsStatusCode['pending']['soft']:
                return GData::$mail_data['change_schedule_request']['pending'];

            case GData::$fsStatusCode['pending']['set_room']:
                return GData::$mail_data['change_schedule_request']['pending_set_room'];

            case GData::$fsStatusCode['approve']['normal']:
                return GData::$mail_data['change_schedule_request']['approve'];

            case GData::$fsStatusCode['approve']['soft']:
            case GData::$fsStatusCode['approve']['straight']:
                return GData::$mail_data['change_schedule_request']['approve_straight'];

            default:
                throw new Exception('send mail fixed schedule');
        }
    }

    private function _getBasicMailDataForHeadOfDepartment ()
    {
        return GData::$mail_data['change_schedule_request']['notify_head_of_department'];
    }

    private function _setUpData (array &$data, FixedSchedule $fixedSchedule)
    {
        $data = array_merge($data, [
            'recipient'      => $fixedSchedule->schedule->moduleClass->teacher->account->email,
            'fixed_schedule' => $fixedSchedule->getOriginal(),
            'module_class'   => $fixedSchedule->schedule->moduleClass->getOriginal(),
            'teacher'        => $fixedSchedule->schedule->moduleClass->teacher->getOriginal(),
            'fs_status_code' => GData::$fsStatusCode,
        ]);
    }

    private function _sendMailNotification ($data)
    {
        $this->mailService->sendFixedScheduleMailNotification($data);
    }
}
