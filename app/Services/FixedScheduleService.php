<?php

namespace App\Services;

use Exception;
use App\Helpers\GData;
use App\Helpers\GFArray;
use App\Helpers\GFunction;
use Illuminate\Support\Arr;
use App\Models\FixedSchedule;
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
    public function createFixedSchedule ($fixedScheduleArr)
    {
        $this->_completeInputData($fixedScheduleArr);
        $fixedSchedule = $this->fixedScheduleRepository->insertGetObject($fixedScheduleArr);
        $this->_checkIfNeedToUpdateSchedule($fixedSchedule);
        $this->_sendMail($fixedSchedule);

        return $fixedSchedule->id;
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
    private function _getMailData (int $status) : array
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
                return GData::$mail_data['change_schedule_request']['confirm'];
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

    /**
     * @throws Exception
     */
    private function _sendMail (FixedSchedule $fixedSchedule)
    {
        if ($fixedSchedule->status != 4)
        {
            try
            {
                $package = [
                    'basic_data'     => $this->_getMailData($fixedSchedule->status),
                    'fixed_schedule' => $fixedSchedule,
                ];
                $this->mailService->sendFixedScheduleMailNotify($package);
            }
            catch (Exception $exception)
            {
                GFunction::printError($exception);
            }
        }
    }

    /**
     * @throws Exception
     */
    public function updateFixedSchedule ($fixedScheduleArr)
    {
        $this->_completeInputData($fixedScheduleArr);
        $this->fixedScheduleRepository->updateByIds($fixedScheduleArr['id'],
                                                    Arr::except($fixedScheduleArr, ['id']));
        $fixedSchedule = $this->_getFixedScheduleById($fixedScheduleArr['id']);
        $this->_checkIfNeedToUpdateSchedule($fixedSchedule);
        $this->_sendMail($fixedSchedule);
    }

    private function _completeInputData (&$fixedScheduleArr)
    {
        if (isset($fixedScheduleArr['time']))
        {
            switch ($fixedScheduleArr['status'])
            {
                case 1:
                    $fixedScheduleArr['time_accept'] = $fixedScheduleArr['time'];
                    break;
                case 2:
                    $fixedScheduleArr['time_set_room'] = $fixedScheduleArr['time'];
                    break;
                default:
                    $fixedScheduleArr['time_accept']   = $fixedScheduleArr['time'];
                    $fixedScheduleArr['time_set_room'] = $fixedScheduleArr['time'];
            }
            unset($fixedScheduleArr['time']);
        }
        else if (isset($fixedScheduleArr['reason']))
        {
            $schedule = $this->_getScheduleById($fixedScheduleArr['id_schedule'],
                                                ['date as old_date', 'shift as old_shift',
                                                 'id_room as old_id_room']);

            $fixedScheduleArr = array_merge($fixedScheduleArr, $schedule->getOriginal());;
        }
    }

    private function _checkIfNeedToUpdateSchedule (FixedSchedule $fixedSchedule)
    {
        if (in_array($fixedSchedule->status, [2, 3, 4]))
        {
            $this->_updateScheduleById($fixedSchedule->id_schedule,
                                       GFArray::onlyKeys($fixedSchedule->getOriginal(),
                                                         ['new_date'    => 'date',
                                                          'new_shift'   => 'shift',
                                                          'new_id_room' => 'id_room']));
        }
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