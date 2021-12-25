<?php

namespace App\Services;

use App\Repositories\Contracts\TeacherRepositoryContract;

class TeacherService implements Contracts\TeacherServiceContract
{
    private TeacherRepositoryContract $teacherRepository;

    /**
     * @param TeacherRepositoryContract $teacherRepository
     */
    public function __construct (TeacherRepositoryContract $teacherRepository)
    {
        $this->teacherRepository = $teacherRepository;
    }

    public function getTeachersByIdDepartment ($id_department)
    {
        return $this->teacherRepository->findByIdDepartment2($id_department);
    }
}