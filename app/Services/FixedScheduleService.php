<?php

namespace App\Services;

use Exception;
use App\Models\FixedSchedule;
use App\Events\FixedScheduleUpdated;
use App\Exceptions\InvalidFormRequestException;
use App\Repositories\Contracts\ScheduleRepositoryContract;
use App\Repositories\Contracts\FixedScheduleRepositoryContract;

class FixedScheduleService implements Contracts\FixedScheduleServiceContract
{
    private FixedScheduleRepositoryContract $fixedScheduleRepository;
    private ScheduleRepositoryContract $scheduleRepository;

    /**
     * @param FixedScheduleRepositoryContract $fixedScheduleRepository
     * @param ScheduleRepositoryContract      $scheduleRepository
     */
    public function __construct (FixedScheduleRepositoryContract $fixedScheduleRepository,
                                 ScheduleRepositoryContract      $scheduleRepository)
    {
        $this->fixedScheduleRepository = $fixedScheduleRepository;
        $this->scheduleRepository      = $scheduleRepository;
    }

    public function readMany (array $inputs)
    {
        return $this->fixedScheduleRepository->paginate(['*'], [], [],
                                                        $inputs['pagination'] ?? 20,
                                                        [['filter', $inputs]]);
    }

    public function readManyByIdDepartment (string $idDepartment, array $inputs)
    {
        return $this->fixedScheduleRepository->findByIdDepartment($idDepartment, $inputs);
    }

    public function readManyByIdTeacher (string $idTeacher, array $inputs)
    {
        return $this->fixedScheduleRepository->findByIdTeacher($idTeacher, $inputs);
    }

    /**
     * @throws Exception
     */
    public function create ($fixedScheduleArr)
    {
        $this->_completeCreateInputs($fixedScheduleArr);
        $fixedSchedule = $this->fixedScheduleRepository->insertGetObject($fixedScheduleArr);
        $this->_sendMailNotification($fixedSchedule);
        return $fixedSchedule->id;
    }

    private function _completeCreateInputs (array &$fixedScheduleArr)
    {
        switch ($fixedScheduleArr['type'] ?? null)
        {
            case null:
                $fixedScheduleArr['status'] = 0;
                break;

            case 'soft':
                $fixedScheduleArr['status'] = 5;
                break;

            case 'hard':
                $fixedScheduleArr['status'] = 4;
                break;
        }
        unset($fixedScheduleArr['type']);

        $schedule = $this->_readScheduleById($fixedScheduleArr['id_schedule'],
                                             ['date as old_date', 'shift as old_shift',
                                              'id_room as old_id_room']);

        $fixedScheduleArr = array_merge($fixedScheduleArr, $schedule->getOriginal());
    }

    private function _readFixedScheduleById ($ids, array $columns = ['*'])
    {
        return $this->fixedScheduleRepository->findByIds($ids, $columns);
    }

    private function _readScheduleById ($id, array $columns = ['*'])
    {
        return $this->scheduleRepository->findByIds($id, $columns);
    }

    /**
     * @param string $idFixedSchedule *
     *
     * @throws Exception
     */
    public function update (string $idFixedSchedule, array $fixedScheduleArr)
    {
        $fixedSchedule = $this->_readFixedScheduleById($idFixedSchedule);
        $this->_completeUpdateInputs($fixedScheduleArr, $fixedSchedule);
        $this->fixedScheduleRepository->updateByIds($idFixedSchedule, $fixedScheduleArr);
        $this->_sendMailNotification($fixedSchedule);
    }

    /**
     * @throws InvalidFormRequestException
     */
    private function _completeUpdateInputs (array &$fixedScheduleArr, FixedSchedule &$fixedSchedule)
    {
        switch ($fixedScheduleArr['type'])
        {
            case 'accept':
                $fixedSchedule->status = 1;

                if (!is_null($fixedSchedule->intend_time))
                {
                    $fixedSchedule->status = 5;
                }

                if (!is_null($fixedSchedule->new_id_room))
                {
                    $fixedSchedule->status = 3;
                }

                $fixedSchedule->accepted_at = $fixedScheduleArr['accepted_at'];
                break;

            case 'set_room':
                $fixedSchedule->status        = 2;
                $fixedSchedule->new_id_room   = $fixedScheduleArr['new_id_room'];
                $fixedSchedule->time_set_room = $fixedScheduleArr['time_set_room'];

                break;

            case 'deny':
                if (auth()->user()->accountable_type == 'App\Models\Teacher')
                {
                    $fixedSchedule->status = -1;
                }
                else
                {
                    $fixedSchedule->status = -2;
                }

                $fixedSchedule->reason_deny = $fixedScheduleArr['reason_deny'];
                break;

            case 'cancel':
                $fixedSchedule->status = -3;
                break;

            case 'intend_time';
                $fixedSchedule->status      = 0;
                $fixedSchedule->new_date    = $fixedScheduleArr['new_date'];
                $fixedSchedule->new_shift   = $fixedScheduleArr['new_shift'];
                $fixedSchedule->new_id_room = $fixedScheduleArr['new_id_room'] ?? null;
                break;

            default:
                throw new InvalidFormRequestException();
        }
        unset($fixedScheduleArr['type']);
    }

    private function _sendMailNotification (FixedSchedule $fixedSchedule)
    {
        if ($fixedSchedule->status = 4)
        {
            return;
        }

        FixedScheduleUpdated::dispatch($fixedSchedule);
    }
}