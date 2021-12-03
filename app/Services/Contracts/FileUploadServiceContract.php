<?php

namespace App\Services\Contracts;

interface FileUploadServiceContract
{
    public function importRollCallFile ($input);

    public function importScheduleFile ($input);

    public function importExamScheduleFile ($input);
}