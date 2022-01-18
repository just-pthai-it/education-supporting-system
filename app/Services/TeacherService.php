<?php

namespace App\Services;

use App\Repositories\Contracts\TeacherRepositoryContract;
use App\Repositories\Contracts\ScheduleRepositoryContract;
use App\Repositories\Contracts\ExamScheduleRepositoryContract;
use App\Repositories\Contracts\FixedScheduleRepositoryContract;

class TeacherService implements Contracts\TeacherServiceContract
{
    private TeacherRepositoryContract $teacherRepository;
    private FixedScheduleRepositoryContract $fixedScheduleRepository;
    private ExamScheduleRepositoryContract $examScheduleRepository;
    private ScheduleRepositoryContract $scheduleDepository;

    /**
     * @param TeacherRepositoryContract       $teacherRepository
     * @param FixedScheduleRepositoryContract $fixedScheduleRepository
     * @param ExamScheduleRepositoryContract  $examScheduleRepository
     * @param ScheduleRepositoryContract      $scheduleDepository
     */
    public function __construct (TeacherRepositoryContract       $teacherRepository,
                                 FixedScheduleRepositoryContract $fixedScheduleRepository,
                                 ExamScheduleRepositoryContract  $examScheduleRepository,
                                 ScheduleRepositoryContract      $scheduleDepository)
    {
        $this->teacherRepository       = $teacherRepository;
        $this->fixedScheduleRepository = $fixedScheduleRepository;
        $this->examScheduleRepository  = $examScheduleRepository;
        $this->scheduleDepository      = $scheduleDepository;
    }

    public function getTeachersByIdDepartment ($id_department)
    {
        return $this->teacherRepository->findByIdDepartment2($id_department);
    }

    public function getSchedules ($id_teacher, $start, $end)
    {
        return $this->scheduleDepository->findAllByIdTeacher($id_teacher, $start, $end);
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