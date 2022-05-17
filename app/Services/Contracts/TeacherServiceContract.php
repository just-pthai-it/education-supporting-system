<?php

namespace App\Services\Contracts;

interface TeacherServiceContract
{
    public function read (string $idTeacher);

    public function readMany (array $inputs);


    public function updateExamScheduleTeacherByIdExamSchedule (string $idTeacher,
                                                               string $idExamSchedule,
                                                               array  $inputs);
}