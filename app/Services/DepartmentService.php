<?php

namespace App\Services;

use App\Repositories\Contracts\TeacherRepositoryContract;
use App\Repositories\Contracts\DepartmentRepositoryContract;

class DepartmentService implements Contracts\DepartmentServiceContract
{
    private DepartmentRepositoryContract $departmentRepository;
    private TeacherRepositoryContract $teacherRepository;

    /**
     * @param DepartmentRepositoryContract $departmentRepository
     * @param TeacherRepositoryContract    $teacherRepository
     */
    public function __construct (DepartmentRepositoryContract $departmentRepository,
                                 TeacherRepositoryContract    $teacherRepository)
    {
        $this->departmentRepository = $departmentRepository;
        $this->teacherRepository    = $teacherRepository;
    }

    public function getTeachers (string $id_department, array $inputs)
    {
        return $this->teacherRepository->find(['id', 'name'],
                                              [['id_department', '=', $id_department]], [], [],
                                              [['filter', $inputs]]);
    }
}