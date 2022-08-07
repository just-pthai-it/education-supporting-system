<?php

namespace App\Services;

use Exception;
use App\Helpers\Constants;
use App\Models\FixedSchedule;
use App\Events\FixedScheduleCreatedOrUpdated;
use App\Repositories\Contracts\ScheduleRepositoryContract;
use App\Repositories\Contracts\NotificationRepositoryContract;
use App\Repositories\Contracts\FixedScheduleRepositoryContract;

class FixedScheduleService implements Contracts\FixedScheduleServiceContract
{
    private FixedScheduleRepositoryContract $fixedScheduleRepository;
    private ScheduleRepositoryContract      $scheduleRepository;
    private NotificationRepositoryContract  $notificationRepositoryContract;

    /**
     * @param FixedScheduleRepositoryContract $fixedScheduleRepository
     * @param ScheduleRepositoryContract      $scheduleRepository
     * @param NotificationRepositoryContract  $notificationRepositoryContract
     */
    public function __construct (FixedScheduleRepositoryContract $fixedScheduleRepository,
                                 ScheduleRepositoryContract      $scheduleRepository,
                                 NotificationRepositoryContract  $notificationRepositoryContract)
    {
        $this->fixedScheduleRepository        = $fixedScheduleRepository;
        $this->scheduleRepository             = $scheduleRepository;
        $this->notificationRepositoryContract = $notificationRepositoryContract;
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
    public function create (array $inputs)
    {
        $inputs        = $this->_completeCreateInputs($inputs);
        $fixedSchedule = $this->fixedScheduleRepository->insertGetObject($inputs);
        $this->__dispatchRelatedEvents($fixedSchedule);
        return $fixedSchedule->id;
    }

    /**
     * @throws Exception
     */
    private function _completeCreateInputs (array $inputs) : array
    {
        $inputs['status'] = $this->__getSuitableStatusOfCreatedFixedSchedule($inputs['type'] ?? '');
        unset($inputs['type']);

        $schedule = $this->_readScheduleById($inputs['id_schedule'],
                                             ['date as old_date', 'shift as old_shift',
                                              'id_room as old_id_room']);

        return array_merge($inputs, $schedule->getOriginal());
    }

    /**
     * @throws Exception
     */
    private function __getSuitableStatusOfCreatedFixedSchedule (string $type) : int
    {
        switch ($type)
        {
            case '':
                return Constants::FIXED_SCHEDULE_STATUS['pending']['normal'];

            case 'soft':
                return Constants::FIXED_SCHEDULE_STATUS['pending']['soft'];

            case 'hard':
                return Constants::FIXED_SCHEDULE_STATUS['change']['normal'];

            default:
                throw new Exception('Unknown fixed schedule type');
        }
    }

    private function _readFixedScheduleById ($ids, array $columns = ['*'])
    {
        $columns = ['*'];
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
        $this->__dispatchRelatedEvents($fixedSchedule);
        return ['data' => ['status' => $fixedSchedule->status]];
    }

    private function _completeUpdateInputs (array &$fixedScheduleArr, FixedSchedule &$fixedSchedule)
    {
        switch ($fixedScheduleArr['type'])
        {
            case 'accept':

                if (!is_null($fixedSchedule->intend_time) &&
                    $fixedSchedule->status == Constants::FIXED_SCHEDULE_STATUS['pending']['soft'])
                {
                    $fixedSchedule->status = Constants::FIXED_SCHEDULE_STATUS['approve']['soft'];
                }
                else if (!is_null($fixedSchedule->new_id_room) &&
                         $fixedSchedule->status ==
                         Constants::FIXED_SCHEDULE_STATUS['pending']['normal'])
                {
                    $fixedSchedule->status = Constants::FIXED_SCHEDULE_STATUS['approve']['straight'];
                }
                else if ($fixedSchedule->status ==
                         Constants::FIXED_SCHEDULE_STATUS['pending']['normal'])
                {
                    $fixedSchedule->status = Constants::FIXED_SCHEDULE_STATUS['pending']['set_room'];
                }
                else
                {
                    break;
                }

                $fixedSchedule->accepted_at = $fixedScheduleArr['accepted_at'];
                break;

            case 'set_room':
                if ($fixedSchedule->status ==
                    Constants::FIXED_SCHEDULE_STATUS['pending']['set_room'] &&
                    is_null($fixedSchedule->new_id_room))
                {
                    $fixedSchedule->status      = Constants::FIXED_SCHEDULE_STATUS['approve']['normal'];
                    $fixedSchedule->new_id_room = $fixedScheduleArr['new_id_room'];
                    $fixedSchedule->set_room_at = $fixedScheduleArr['set_room_at'];
                }

                break;

            case 'deny':
                if ($fixedSchedule->status ==
                    Constants::FIXED_SCHEDULE_STATUS['pending']['normal'] ||
                    $fixedSchedule->status == Constants::FIXED_SCHEDULE_STATUS['pending']['soft'])
                {
                    $fixedSchedule->status = Constants::FIXED_SCHEDULE_STATUS['deny']['accept'];
                }
                else if ($fixedSchedule->status ==
                         Constants::FIXED_SCHEDULE_STATUS['pending']['set_room'])
                {
                    $fixedSchedule->status = Constants::FIXED_SCHEDULE_STATUS['deny']['set_room'];
                }
                else
                {
                    break;
                }

                $fixedSchedule->reason_deny = $fixedScheduleArr['reason_deny'];
                break;

            case 'cancel':
                if (in_array($fixedSchedule->status,
                             [Constants::FIXED_SCHEDULE_STATUS['pending']['normal'],
                              Constants::FIXED_SCHEDULE_STATUS['pending']['soft'],
                              Constants::FIXED_SCHEDULE_STATUS['pending']['set_room']]))
                {
                    $fixedSchedule->status = Constants::FIXED_SCHEDULE_STATUS['cancel']['normal'];
                }
                break;
        }
        unset($fixedScheduleArr['type']);
    }

    private function __dispatchRelatedEvents (FixedSchedule $fixedSchedule)
    {
        if ($fixedSchedule->status == Constants::FIXED_SCHEDULE_STATUS['change']['normal'])
        {
            return;
        }

        FixedScheduleCreatedOrUpdated::dispatch($fixedSchedule, request()->user());
    }
}