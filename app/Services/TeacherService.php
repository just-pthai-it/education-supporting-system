<?php

namespace App\Services;

use App\Repositories\Contracts\TeacherRepositoryContract;
use App\Repositories\Contracts\ExamScheduleRepositoryContract;

class TeacherService implements Contracts\TeacherServiceContract
{
    private TeacherRepositoryContract $teacherRepository;
    private ExamScheduleRepositoryContract $examScheduleRepository;

    /**
     * @param TeacherRepositoryContract       $teacherRepository
     * @param ExamScheduleRepositoryContract  $examScheduleRepository
     */
    public function __construct (TeacherRepositoryContract       $teacherRepository,
                                 ExamScheduleRepositoryContract  $examScheduleRepository)
    {
        $this->teacherRepository       = $teacherRepository;
        $this->examScheduleRepository  = $examScheduleRepository;
    }

    public function read (string $idTeacher)
    {
        return $this->teacherRepository->findByIds($idTeacher, ['*'], [],
                                                   [['with', 'department:id,name,id_faculty',
                                                     'department.faculty:id,name']]);
    }

    public function getExamSchedules (string $idTeacher, array $inputs)
    {
        return $this->examScheduleRepository->findByIdTeacher($idTeacher, $inputs);
    }
}