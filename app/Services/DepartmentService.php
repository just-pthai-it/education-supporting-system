<?php

namespace App\Services;

use App\Repositories\Contracts\DepartmentRepositoryContract;
use App\Repositories\Contracts\FixedScheduleRepositoryContract;

class DepartmentService implements Contracts\DepartmentServiceContract
{
    private DepartmentRepositoryContract $departmentRepository;
    private FixedScheduleRepositoryContract $fixedScheduleRepository;

    /**
     * @param DepartmentRepositoryContract    $departmentRepository
     * @param FixedScheduleRepositoryContract $fixedScheduleRepository
     */
    public function __construct (DepartmentRepositoryContract    $departmentRepository,
                                 FixedScheduleRepositoryContract $fixedScheduleRepository)
    {
        $this->departmentRepository    = $departmentRepository;
        $this->fixedScheduleRepository = $fixedScheduleRepository;
    }

    public function getAllDepartments ()
    {
        return $this->departmentRepository->findAllWithDepartments();
    }

    public function getFixedSchedulesByStatus ($id_department, $status)
    {
        return $this->fixedScheduleRepository->findByStatusAndIdDepartment($id_department, $status);
    }
}