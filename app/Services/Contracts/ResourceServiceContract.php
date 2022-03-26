<?php

namespace App\Services\Contracts;

interface ResourceServiceContract
{
    public function importRollCallFile ($input);

    public function importScheduleFile ($input);

    public function importExamScheduleFile ($input);

    public function importCurriculumFile ($input);
}