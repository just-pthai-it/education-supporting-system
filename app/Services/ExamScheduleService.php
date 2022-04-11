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

    public function update ($examSchedule)
    {
        $this->examScheduleRepository->updateByIds($examSchedule['id'],
                                                   Arr::except($examSchedule, ['id']));
    }
}