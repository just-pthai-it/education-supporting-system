<?php

namespace App\Services;


use App\Repositories\Contracts\DataVersionStudentRepositoryContract;
use App\Services\Contracts\DataVersionStudentServiceContract;

class DataVersionStudentService implements DataVersionStudentServiceContract
{
    private DataVersionStudentRepositoryContract $dataVersionStudentDepository;

    /**
     * DataVersionStudentService constructor.
     * @param DataVersionStudentRepositoryContract $dataVersionStudentDepository
     */
    public function __construct (DataVersionStudentRepositoryContract $dataVersionStudentDepository)
    {
        $this->dataVersionStudentDepository = $dataVersionStudentDepository;
    }

    public function getDataVersion ($id_student)
    {
        return $this->dataVersionStudentDepository->get($id_student);
    }
}
