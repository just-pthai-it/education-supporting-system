<?php

namespace App\Services\Contracts;

interface TeacherServiceContract
{
    public function getTeachersByIdDepartment ($id_department);
}