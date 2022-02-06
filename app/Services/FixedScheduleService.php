<?php

namespace App\Services;

use Exception;
use App\Helpers\GData;
use App\Services\Contracts\MailServiceContract;
use App\Repositories\Contracts\ScheduleRepositoryContract;
use App\Repositories\Contracts\FixedScheduleRepositoryContract;

class FixedScheduleService implements Contracts\FixedScheduleServiceContract
{
    private MailServiceContract $mailService;
    private FixedScheduleRepositoryContract $fixedScheduleRepository;
    private ScheduleRepositoryContract $scheduleRepository;

    /**
     * @param MailServiceContract             $mailService
     * @param FixedScheduleRepositoryContract $fixedScheduleRepository
     * @param ScheduleRepositoryContract      $scheduleRepository
     */
    public function __construct (MailServiceContract             $mailService,
                                 FixedScheduleRepositoryContract $fixedScheduleRepository,
                                 ScheduleRepositoryContract      $scheduleRepository)
    {
        $this->mailService             = $mailService;
        $this->fixedScheduleRepository = $fixedScheduleRepository;
        $this->scheduleRepository      = $scheduleRepository;
    }

    /**
     * @throws Exception
     */
    public function createFixedSchedule ($fixed_schedule)
    {
        $this->_fillData($fixed_schedule);
        $this->fixedScheduleRepository->insert($fixed_schedule);
        $this->_sendMail(auth()->user()->email, $fixed_schedule);
    }

    private function _fillData (&$fixed_schedule)
    {
        $schedule = $this->_getScheduleById($fixed_schedule['id_schedule']);

        $fixed_schedule['old_date']    = $schedule->date;
        $fixed_schedule['old_shift']   = $schedule->shift;
        $fixed_schedule['old_id_room'] = $schedule->id_room;
        $fixed_schedule['status']      = 0;
    }

    private function _getFixedSchedules ($ids)
    {
        return $this->fixedScheduleRepository->findByIds($ids);
    }

    private function _getScheduleById ($id)
    {
        return $this->scheduleRepository->findByIds($id, ['date', 'shift', 'id_room']);
    }

    /**
     * @throws Exception
     */
    private function _getMailData (array $data) : array
    {
        switch ($data['status'])
        {
            case -3:
                return array_merge(GData::$mail_data['change_schedule_request']['cancel'], $data);
            case -2:
                return array_merge(GData::$mail_data['change_schedule_request']['deny_room'],
                                   $data);
            case -1:
                return array_merge(GData::$mail_data['change_schedule_request']['deny'], $data);
            case 0:
                return array_merge(GData::$mail_data['change_schedule_request']['confirm'], $data);
            case 1:
                return array_merge(GData::$mail_data['change_schedule_request']['accept'], $data);
            case 2:
                return array_merge(GData::$mail_data['change_schedule_request']['accept_room'],
                                   $data);
            default:
                throw new Exception('send mail fixed schedule');
        }
    }

    /**
     * @throws Exception
     */
    private function _sendMail (string $receiver, array $data)
    {
        $data = $this->_getMailData($data);
        $this->mailService->sendFixedScheduleMailNotify([$receiver], $data);
    }

    /**
     * @throws Exception
     */
    public function updateFixedSchedule ($fixed_schedule)
    {
        $id = array_shift($fixed_schedule);
        $this->fixedScheduleRepository->updateByIds($id, $fixed_schedule);
        $fixed_schedule = $this->_getFixedSchedules($id)->getOriginal();
        $this->_sendMail($this->_getTeacherEmailByIdSchedule($fixed_schedule['id_schedule']),
                         $fixed_schedule);
    }

    private function _getTeacherEmailByIdSchedule (int $id_schedule)
    {
        return $this->scheduleRepository->findTeacherEmailByIdSchedule($id_schedule);
    }

    public function paginateFixedSchedulesByStatus (string $status, string $pagination)
    {
        return $this->fixedScheduleRepository->paginate(['*'], [], [['id', 'desc']],
                                                        $pagination, [['status', $status]]);
    }
}