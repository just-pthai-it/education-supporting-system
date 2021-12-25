<?php

namespace App\Repositories\Contracts;

interface TeacherRepositoryContract
{
    public function findById ($id);

    public function findByIdDepartment ($id_department);

    public function findByIdDepartment2 ($id_department);

}