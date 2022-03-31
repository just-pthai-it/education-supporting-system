<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Arr;
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

    /**
     * @throws Exception
     */
    public function create ($fixedScheduleArr)
    {
        $this->_completeInputData($fixedScheduleArr);
        $fixedSchedule = $this->fixedScheduleRepository->insertGetObject($fixedScheduleArr);
        FixedScheduleUpdated::dispatch($fixedSchedule);

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

    /**
     * @throws Exception
     */
    public function update ($fixedScheduleArr)
    {
        $this->_completeInputData($fixedScheduleArr);
        $this->fixedScheduleRepository->updateByIds($fixedScheduleArr['id'],
                                                    Arr::except($fixedScheduleArr, ['id']));
        $fixedSchedule = $this->_getFixedScheduleById($fixedScheduleArr['id']);
        FixedScheduleUpdated::dispatch($fixedSchedule);
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

    public function paginateFixedSchedulesByStatus (string $status, string $pagination)
    {
        return $this->fixedScheduleRepository->paginate(['*'], [], [],
                                                        $inputs['pagination'] ?? 20,
                                                        [['filter', $inputs]]);
    }

    public function read (array $inputs)
    {
        // TODO: Implement read() method.
    }
}