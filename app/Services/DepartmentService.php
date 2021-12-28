<?php

namespace App\Services;

use App\Repositories\Contracts\DepartmentRepositoryContract;

class DepartmentService implements Contracts\DepartmentServiceContract
{
    private DepartmentRepositoryContract $departmentRepository;

    /**
     * @param DepartmentRepositoryContract $departmentRepository
     */
    public function __construct (DepartmentRepositoryContract $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function getAllDepartments ()
    {
        return $this->departmentRepository->findAllWithDepartments();
    }
}