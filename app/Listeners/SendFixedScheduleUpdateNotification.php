<?php

namespace App\Listeners;

use Exception;
use App\Helpers\GData;
use App\Models\Teacher;
use App\Helpers\Constants;
use App\Models\FixedSchedule;
use App\Events\FixedScheduleUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\Contracts\MailServiceContract;
use function App\Helpers\replaceStringKeys;

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
                              'schedule.moduleClass:id,name,id_module,id_teacher',
                              'schedule.moduleClass.module:id,id_department',
                              'schedule.moduleClass.teacher:id,name,is_female,id_department',
                              'schedule.moduleClass.teacher.account:accountable_id,email']);
    }

    /**
     * @throws Exception
     */
    private function _sendMailNotificationToHeadOfDepartment (FixedSchedule $fixedSchedule)
    {
        if (!in_array($fixedSchedule->status,
                      [Constants::FIXED_SCHEDULE_STATUS['pending']['normal'],
                       Constants::FIXED_SCHEDULE_STATUS['pending']['soft']]))
        {
            return;
        }

        $idDepartment = $fixedSchedule->schedule->moduleClass->module->id_department;
        $teacher      = $this->_readHeadOfDepartment($idDepartment);
        $mailData     = $this->_getBasicMailDataForHeadOfDepartment();
        $mailContent  = replaceStringKeys($mailData['mail_content'],
                                          [':teacher_gender'  => $teacher->is_female ? 'cô' : 'thầy',
                                           ':teacher_name'    => $teacher->name,
                                           ':department_name' => $teacher->department->name,]);

        $this->_sendMailNotification(['view'      => $mailData['view'],
                                      'subject'   => $mailData['subject'],
                                      'recipient' => $teacher->account->email,
                                      'data'      => ['content' => $mailContent,],]);
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
            case Constants::FIXED_SCHEDULE_STATUS['cancel']['normal']:
                return GData::$mail_data['change_schedule_request']['cancel'];

            case Constants::FIXED_SCHEDULE_STATUS['deny']['set_room']:
                return GData::$mail_data['change_schedule_request']['deny_set_room'];

            case Constants::FIXED_SCHEDULE_STATUS['deny']['accept']:
                return GData::$mail_data['change_schedule_request']['deny_accept'];

            case Constants::FIXED_SCHEDULE_STATUS['pending']['normal']:
            case Constants::FIXED_SCHEDULE_STATUS['pending']['soft']:
                return GData::$mail_data['change_schedule_request']['pending'];

            case Constants::FIXED_SCHEDULE_STATUS['pending']['set_room']:
                return GData::$mail_data['change_schedule_request']['pending_set_room'];

            case Constants::FIXED_SCHEDULE_STATUS['approve']['normal']:
                return GData::$mail_data['change_schedule_request']['approve'];

            case Constants::FIXED_SCHEDULE_STATUS['approve']['soft']:
            case Constants::FIXED_SCHEDULE_STATUS['approve']['straight']:
                return GData::$mail_data['change_schedule_request']['approve_straight'];

            default:
                throw new Exception('send mail fixed schedule, status code: ' . $status);
        }
    }

    private function _getBasicMailDataForHeadOfDepartment () : array
    {
        return Constants::FIXED_SCHEDULE_CREATED_NOTIFICATION;
    }

    private function _setUpData (array &$data, FixedSchedule $fixedSchedule)
    {
        $mailContent = replaceStringKeys($data['mail_content'],
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

    private function _sendMailNotification ($data)
    {
        $this->mailService->sendFixedScheduleMailNotification($data);
    }
}
