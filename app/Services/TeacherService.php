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

    public function read (string $idTeacher)
    {
        return $this->teacherRepository->find(['*'], [['id', '=', $idTeacher]], [], [],
                                              [['with', 'department:id,name,id_faculty',
                                                'department.faculty:id,name',
                                                'account:email,phone,accountable_id']])[0];
    }

    public function readMany (array $inputs) : array
    {
        $teachers = $this->teacherRepository->find(['id', 'name'], [], [], [],
                                                   [['filter', $inputs]]);
        return ['data' => $teachers];
    }
}