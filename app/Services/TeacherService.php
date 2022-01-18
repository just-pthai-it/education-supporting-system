<?php

namespace App\Services;

use App\Repositories\Contracts\TeacherRepositoryContract;
use App\Repositories\Contracts\ExamScheduleRepositoryContract;
use App\Repositories\Contracts\FixedScheduleRepositoryContract;

class TeacherService implements Contracts\TeacherServiceContract
{
    private TeacherRepositoryContract $teacherRepository;
    private FixedScheduleRepositoryContract $fixedScheduleRepository;
    private ExamScheduleRepositoryContract $examScheduleRepository;

    /**
     * @param TeacherRepositoryContract       $teacherRepository
     * @param FixedScheduleRepositoryContract $fixedScheduleRepository
     * @param ExamScheduleRepositoryContract  $examScheduleRepository
     */
    public function __construct (TeacherRepositoryContract       $teacherRepository,
                                 FixedScheduleRepositoryContract $fixedScheduleRepository,
                                 ExamScheduleRepositoryContract  $examScheduleRepository)
    {
        $this->teacherRepository       = $teacherRepository;
        $this->fixedScheduleRepository = $fixedScheduleRepository;
        $this->examScheduleRepository  = $examScheduleRepository;
    }

    public function getTeachersByIdDepartment ($id_department)
    {
        return $this->teacherRepository->findByIdDepartment2($id_department);
    }

    public function getExamSchedules ($id_teacher, $start, $end)
    {
        return $this->examScheduleRepository->findByIdTeacher($id_teacher, $start, $end);
    }

    public function getFixedSchedulesByStatus ($id_teacher, $status)
    {
        return $this->fixedScheduleRepository->findByStatusAndIdTeacher($id_teacher, $status);
    }
}