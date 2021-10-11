<?php

namespace App\Services;

use App\Repositories\Contracts\ExamScheduleRepositoryContract;
use App\Services\Contracts\ExamScheduleServiceContract;

class ExamScheduleService implements ExamScheduleServiceContract
{
    private ExamScheduleRepositoryContract $examScheduleDepository;

    /**
     * ExamScheduleService constructor.
     * @param ExamScheduleRepositoryContract $examScheduleDepository
     */
    public function __construct (ExamScheduleRepositoryContract $examScheduleDepository)
    {
        $this->examScheduleDepository = $examScheduleDepository;
    }

    public function get ($id_student)
    {
        return $this->examScheduleDepository->get($id_student);
    }
}
