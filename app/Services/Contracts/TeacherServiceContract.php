<?php

namespace App\Services\Contracts;

interface TeacherServiceContract
{
    public function getById ($id_teacher);

    public function getSchedules ($id_teacher, array $inputs);

    public function getExamSchedules ($id_teacher, array $inputs);

    public function getFixedSchedules ($id_teacher, array $inputs);

    public function getModuleClasses ($id_teacher, array $inputs);
}