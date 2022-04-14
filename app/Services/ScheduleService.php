<?php

namespace App\Services;

use Illuminate\Support\Arr;
use App\Repositories\Contracts\ScheduleRepositoryContract;

class ScheduleService implements Contracts\ScheduleServiceContract
{
    private ScheduleRepositoryContract $scheduleDepository;

    /**
     * @param ScheduleRepositoryContract $scheduleDepository
     */
    public function __construct (ScheduleRepositoryContract $scheduleDepository)
    {
        $this->scheduleDepository = $scheduleDepository;
    }

    public function readManyByIdDepartment (string $idDepartment, array $inputs)
    {
        return $this->scheduleDepository->findAllByIdDepartment($idDepartment, $inputs);
    }

    public function readManyByIdTeacher (string $idTeacher, array $inputs)
    {
        return $this->scheduleDepository->findAllByIdTeacher($idTeacher, $inputs);
    }

    public function update (string $idSchedule, array $scheduleArr)
    {
        $this->scheduleDepository->updateByIds($idSchedule, $scheduleArr);
    }
}
