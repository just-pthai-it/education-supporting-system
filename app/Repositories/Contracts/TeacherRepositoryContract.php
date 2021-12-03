<?php

namespace App\Repositories\Contracts;

interface TeacherRepositoryContract
{
    public function findById ($id);

    public function findAllByIdDepartment($id_department);

}