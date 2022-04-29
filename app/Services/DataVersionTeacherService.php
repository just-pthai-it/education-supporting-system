<?php

namespace App\Services;

use App\Repositories\Contracts\DataVersionTeacherRepositoryContract;

class DataVersionTeacherService implements Contracts\DataVersionTeacherServiceContract
{
    private DataVersionTeacherRepositoryContract $dataVersionTeacherDepository;

    /**
     * @param DataVersionTeacherRepositoryContract $dataVersionTeacherDepository
     */
    public function __construct (DataVersionTeacherRepositoryContract $dataVersionTeacherDepository)
    {
        $this->dataVersionTeacherDepository = $dataVersionTeacherDepository;
    }

        DataVersionTeacherServiceContract::class  => DataVersionTeacherService::class,

}