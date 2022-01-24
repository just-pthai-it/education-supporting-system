<?php

namespace App\Services;

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

    public function updateExamSchedule ($exam_schedule)
    {
        $id = array_shift($exam_schedule);
        $this->examScheduleRepository->updateByIds($id, $exam_schedule);
    }
}