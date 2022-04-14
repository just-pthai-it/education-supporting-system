<?php

namespace App\Services\Contracts;

interface TeacherServiceContract
{
    public function read (string $idTeacher);

    public function getExamSchedules (string $idTeacher, array $inputs);
}