<?php

namespace App\Services\Contracts;

interface FileUploadServiceContract
{
    public function importRollCallFile ($file, $id_training_type);
}