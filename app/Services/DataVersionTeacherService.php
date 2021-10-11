<?php


namespace App\Services;


use App\Repositories\Contracts\DataVersionTeacherRepositoryContract;

class DataVersionTeacherService implements Contracts\DataVersionTeacherServiceContract
{
    private DataVersionTeacherRepositoryContract $dataVersionTeacherDepository;

    /**
     * DataVersionTeacherService constructor.
     * @param DataVersionTeacherRepositoryContract $dataVersionTeacherDepository
     */
    public function __construct (DataVersionTeacherRepositoryContract $dataVersionTeacherDepository)
    {
        $this->dataVersionTeacherDepository = $dataVersionTeacherDepository;
    }

    public function getDataVersion ($id_teacher)
    {
        return $this->dataVersionTeacherDepository->get($id_teacher);
    }
}
