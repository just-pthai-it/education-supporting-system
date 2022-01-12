<?php

namespace App\Services;

use App\Http\Resources\FixedScheduleResource;
use App\Repositories\Contracts\TeacherRepositoryContract;
use App\Repositories\Contracts\FixedScheduleRepositoryContract;

class TeacherService implements Contracts\TeacherServiceContract
{
    private TeacherRepositoryContract $teacherRepository;
    private FixedScheduleRepositoryContract $fixedScheduleRepository;

    /**
     * @param TeacherRepositoryContract       $teacherRepository
     * @param FixedScheduleRepositoryContract $fixedScheduleRepository
     */
    public function __construct (TeacherRepositoryContract       $teacherRepository,
                                 FixedScheduleRepositoryContract $fixedScheduleRepository)
    {
        $this->teacherRepository       = $teacherRepository;
        $this->fixedScheduleRepository = $fixedScheduleRepository;
    }

    public function getTeachersByIdDepartment ($id_department)
    {
        return $this->teacherRepository->findByIdDepartment2($id_department);
    }

    public function getFixedSchedulesByStatus ($id_teacher, $status)
    {
        return $this->fixedScheduleRepository->findByStatusAndIdTeacher($id_teacher, $status);
    }
}