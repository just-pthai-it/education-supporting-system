<?php

namespace App\Services;

use Exception;
use App\Helpers\GData;
use App\Helpers\GFArray;
use Illuminate\Support\Arr;
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
        $this->_completeInputData($fixed_schedule);
        $id = $this->fixedScheduleRepository->insertGetId($fixed_schedule);
        $this->_sendMail(auth()->user()->email, $fixed_schedule);

        return $id;
    }

    private function _getFixedScheduleById ($ids, array $columns = ['*'])
    {
        return $this->fixedScheduleRepository->findByIds($ids, $columns);
    }

    private function _getScheduleById ($id, array $columns = ['*'])
    {
        return $this->scheduleRepository->findByIds($id, $columns);
    }

    private function _updateScheduleById ($id, array $columns = ['*'])
    {
        $this->scheduleRepository->updateByIds($id, $columns);
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
            case 3:
                return array_merge(GData::$mail_data['change_schedule_request']['accept_straight'],
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
        $this->_completeInputData($fixed_schedule);
        $this->fixedScheduleRepository->updateByIds($fixed_schedule['id'],
                                                    Arr::except($fixed_schedule, ['id']));
        $fixed_schedule = $this->_getFixedScheduleById($fixed_schedule['id']);
        $this->_checkIfNeedToUpdateSchedule($fixed_schedule->getOriginal());
        $this->_sendMail($this->_getTeacherEmailByIdSchedule($fixed_schedule['id_schedule']),
                         $fixed_schedule->getOriginal());
    }

    private function _completeInputData (&$fixedSchedule)
    {
        if (isset($fixedSchedule['time']))
        {
            switch ($fixedSchedule['status'])
            {
                case 1:
                    $fixedSchedule['time_accept'] = $fixedSchedule['time'];
                    break;
                case 2:
                    $fixedSchedule['time_set_room'] = $fixedSchedule['time'];
                    break;
                default:
                    $fixedSchedule['time_accept']   = $fixedSchedule['time'];
                    $fixedSchedule['time_set_room'] = $fixedSchedule['time'];
            }
            unset($fixedSchedule['time']);
        }
        else if (isset($fixedSchedule['reason']))
        {
            $schedule = $this->_getScheduleById($fixedSchedule['id_schedule'],
                                                ['date as old_date', 'shift as old_shift',
                                                 'id_room as old_id_room']);

            $fixedSchedule = array_merge($fixedSchedule, $schedule->getOriginal());;
            $fixedSchedule['status'] = 0;
        }
    }

    private function _checkIfNeedToUpdateSchedule (array $fixedSchedule)
    {
        if (in_array($fixedSchedule['status'], [2, 3]))
        {
            $this->_updateScheduleById($fixedSchedule['id_schedule'],
                                       GFArray::onlyKeys($fixedSchedule,
                                                         ['new_date'    => 'date',
                                                          'new_shift'   => 'shift',
                                                          'new_id_room' => 'id_room']));
        }
    }

    private function _getTeacherEmailByIdSchedule (int $id_schedule)
    {
        return $this->scheduleRepository->findTeacherEmailByIdSchedule($id_schedule);
    }

    public function paginateFixedSchedulesByStatus (string $status, string $pagination)
    {
        $array  = [];
        $array2 = [];
        if ($status == 'all')
        {
            $array = [['status', 'in', [-2, 1, 2]]];
        }
        else
        {
            $array2 = [['status', $status]];
        }

        return $this->fixedScheduleRepository->paginate(['*'], $array, [['id', 'desc']],
                                                        $pagination, $array2);
    }
}