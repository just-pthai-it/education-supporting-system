<?php

namespace App\Services;

use Illuminate\Support\Arr;
use App\Repositories\Contracts\ScheduleRepositoryContract;
use App\Repositories\Contracts\DepartmentRepositoryContract;

class ScheduleService implements Contracts\ScheduleServiceContract
{
    private ScheduleRepositoryContract $scheduleDepository;
    private DepartmentRepositoryContract $departmentRepository;

    /**
     * @param ScheduleRepositoryContract   $scheduleDepository
     * @param DepartmentRepositoryContract $departmentRepository
     */
    public function __construct (ScheduleRepositoryContract   $scheduleDepository,
                                 DepartmentRepositoryContract $departmentRepository)
    {
        $this->scheduleDepository   = $scheduleDepository;
        $this->departmentRepository = $departmentRepository;
    }

    public function readManyByIdDepartment (string $idDepartment, array $inputs)
    {
        return $this->scheduleDepository->findAllByIdDepartment($idDepartment, $inputs);
    }

    public function readManyByIdTeacher (string $idTeacher, array $inputs)
    {
        return $this->scheduleDepository->findAllByIdTeacher($idTeacher, $inputs);
    }

    public function readManyByTeachersInDepartment (string $idDepartment)
    {
//        $idTeachers = $this->departmentRepository->pluck($idDepartment, ['id'])
    }

    public function update (string $idSchedule, array $scheduleArr)
    {
        $this->scheduleDepository->updateByIds($idSchedule, $scheduleArr);
    }
}
