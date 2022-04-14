<?php

namespace App\Services;

use App\Repositories\Contracts\TeacherRepositoryContract;
use App\Repositories\Contracts\DepartmentRepositoryContract;
use App\Repositories\Contracts\ExamScheduleRepositoryContract;

class DepartmentService implements Contracts\DepartmentServiceContract
{
    private DepartmentRepositoryContract $departmentRepository;
    private ExamScheduleRepositoryContract $examScheduleRepository;
    private TeacherRepositoryContract $teacherRepository;

    /**
     * @param DepartmentRepositoryContract    $departmentRepository
     * @param ExamScheduleRepositoryContract  $examScheduleRepository
     * @param TeacherRepositoryContract       $teacherRepository
     */
    public function __construct (DepartmentRepositoryContract    $departmentRepository,
                                 ExamScheduleRepositoryContract  $examScheduleRepository,
                                 TeacherRepositoryContract       $teacherRepository)
    {
        $this->departmentRepository    = $departmentRepository;
        $this->examScheduleRepository  = $examScheduleRepository;
        $this->teacherRepository       = $teacherRepository;
    }

    public function getExamSchedules ($id_department, array $inputs)
    {
        return $this->examScheduleRepository->findByIdDepartment($id_department, $inputs);
    }

    public function getTeachers (string $id_department, array $inputs)
    {
        return $this->teacherRepository->find(['id', 'name'],
                                              [['id_department', '=', $id_department]], [], [],
                                              [['filter', $inputs]]);
    }
}