<?php

namespace App\Services;

use Illuminate\Support\Arr;
use App\Repositories\Contracts\ExamScheduleRepositoryContract;

class ExamScheduleService implements Contracts\ExamScheduleServiceContract
{
    private ExamScheduleRepositoryContract $examScheduleRepository;

    /**
     * @param ExamScheduleRepositoryContract $examScheduleRepository
     */
    public function __construct (ExamScheduleRepositoryContract $examScheduleRepository)
    {
        $this->examScheduleRepository = $examScheduleRepository;
    }

    public function readManyByIdDepartment (string $idDepartment, array $inputs)
    {
        return $this->examScheduleRepository->findByIdDepartment($idDepartment, $inputs);
    }

    public function readManyByIdTeacher (string $idTeacher, array $inputs)
    {
        return $this->examScheduleRepository->findByIdTeacher($idTeacher, $inputs);
    }

    public function update ($examSchedule)
    {
        $this->examScheduleRepository->updateByIds($examSchedule['id'],
                                                   Arr::except($examSchedule, ['id']));
    }
}