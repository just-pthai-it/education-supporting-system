<?php

namespace App\Repositories\Contracts;

interface ExamScheduleRepositoryContract
{
    public function get ($id_student);

    public function getLatestSchoolYear ($id_student);

    public function insertMultiple ($data);

    public function upsert ($data);

    public function delete ($id_student, $id_school_year);
}