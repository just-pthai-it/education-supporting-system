<?php

namespace App\Services\Contracts;

interface TeacherServiceContract
{
    public function read (string $idTeacher);

    public function getSchedules (string $idTeacher, array $inputs);

    public function getExamSchedules (string $idTeacher, array $inputs);

    public function getFixedSchedules (string $idTeacher, array $inputs);

    public function getModuleClasses (string $idTeacher, array $inputs);
}