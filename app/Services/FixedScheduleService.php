<?php

namespace App\Services;

use Exception;
use App\Helpers\GData;
use App\Models\FixedSchedule;
use App\Events\FixedScheduleUpdated;
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
                $fixedScheduleArr['status'] = GData::$fsStatusCode['pending']['normal'];
                break;

            case 'soft':
                $fixedScheduleArr['status'] = GData::$fsStatusCode['pending']['soft'];
                break;

            case 'hard':
                $fixedScheduleArr['status'] = GData::$fsStatusCode['change']['normal'];
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
    public function update (string $idFixedSchedule, array $fixedScheduleArr) : array
    {
        $fixedSchedule = $this->_readFixedScheduleById($idFixedSchedule);
        $this->_completeUpdateInputs($fixedScheduleArr, $fixedSchedule);
        $fixedSchedule->save();
        $this->_sendMailNotification($fixedSchedule);
        return ['data' => ['status' => $fixedSchedule->status]];
    }

    private function _completeUpdateInputs (array &$fixedScheduleArr, FixedSchedule &$fixedSchedule)
    {
        switch ($fixedScheduleArr['type'])
        {
            case 'accept':

                if (!is_null($fixedSchedule->intend_time) &&
                    $fixedSchedule->status == GData::$fsStatusCode['pending']['soft'])
                {
                    $fixedSchedule->status = GData::$fsStatusCode['approve']['soft'];
                }
                else if (!is_null($fixedSchedule->new_id_room) &&
                         $fixedSchedule->status == GData::$fsStatusCode['pending']['normal'])
                {
                    $fixedSchedule->status = GData::$fsStatusCode['approve']['straight'];
                }
                else if ($fixedSchedule->status == GData::$fsStatusCode['pending']['normal'])
                {
                    $fixedSchedule->status = GData::$fsStatusCode['pending']['set_room'];
                }
                else
                {
                    break;
                }

                $fixedSchedule->accepted_at = $fixedScheduleArr['accepted_at'];
                break;

            case 'set_room':
                if ($fixedSchedule->status == GData::$fsStatusCode['pending']['set_room'] &&
                    is_null($fixedSchedule->new_id_room))
                {
                    $fixedSchedule->status      = GData::$fsStatusCode['approve']['normal'];
                    $fixedSchedule->new_id_room = $fixedScheduleArr['new_id_room'];
                    $fixedSchedule->set_room_at = $fixedScheduleArr['set_room_at'];
                }

                break;

            case 'deny':
                if ($fixedSchedule->status == GData::$fsStatusCode['pending']['normal'] ||
                    $fixedSchedule->status == GData::$fsStatusCode['pending']['soft'])
                {
                    $fixedSchedule->status = GData::$fsStatusCode['deny']['accept'];
                }
                else if ($fixedSchedule->status == GData::$fsStatusCode['pending']['set_room'])
                {
                    $fixedSchedule->status = GData::$fsStatusCode['deny']['set_room'];
                }
                else
                {
                    break;
                }

                $fixedSchedule->reason_deny = $fixedScheduleArr['reason_deny'];
                break;

            case 'cancel':
                if (in_array($fixedSchedule->status, [GData::$fsStatusCode['pending']['normal'],
                                                      GData::$fsStatusCode['pending']['soft'],
                                                      GData::$fsStatusCode['pending']['set_room']]))
                {
                    $fixedSchedule->status = GData::$fsStatusCode['cancel']['normal'];
                }
                break;
        }
        unset($fixedScheduleArr['type']);
    }

    private function _sendMailNotification (FixedSchedule $fixedSchedule)
    {
        if ($fixedSchedule->status == GData::$fsStatusCode['change']['normal'])
        {
            return;
        }

        FixedScheduleUpdated::dispatch($fixedSchedule);
    }
}