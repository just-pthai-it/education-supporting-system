<?php

namespace App\Services\Contracts;

interface ResourceServiceContract
{
    public function importRollCallFile (array $inputs);

    public function importScheduleFile (array $inputs);

    public function importExamScheduleFile (array $inputs);

    public function importCurriculumFile (array $inputs);
}