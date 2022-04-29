<?php

namespace App\Services;

use App\Repositories\Contracts\DataVersionStudentRepositoryContract;

class DataVersionStudentService implements Contracts\DataVersionStudentServiceContract
{
    private DataVersionStudentRepositoryContract $dataVersionStudentDepository;

    /**
     * @param DataVersionStudentRepositoryContract $dataVersionStudentDepository
     */
    public function __construct (DataVersionStudentRepositoryContract $dataVersionStudentDepository)
    {
        $this->dataVersionStudentDepository = $dataVersionStudentDepository;
    }


}