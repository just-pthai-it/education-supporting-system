<?php

namespace App\Services;

use Illuminate\Support\Arr;
use App\Http\Resources\ExamScheduleResource;
use App\Repositories\Contracts\ExamScheduleRepositoryContract;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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

    public function readManyByIdDepartment (string $idDepartment,
                                            array  $inputs) : AnonymousResourceCollection
    {
        $examSchedules = $this->examScheduleRepository->findByIdDepartment($idDepartment, $inputs);
        return ExamScheduleResource::collection($examSchedules);
    }

    public function readManyByIdTeacher (string $idTeacher, array $inputs) : AnonymousResourceCollection
    {
        $examSchedules = $this->examScheduleRepository->findByIdTeacher($idTeacher, $inputs);
        return ExamScheduleResource::collection($examSchedules);
    }

    public function update ($examSchedule)
    {
        $this->examScheduleRepository->updateByIds($examSchedule['id'],
                                                   Arr::except($examSchedule, ['id']));
    }

    public function updateV1 (string $idExamSchedule, array $inputs)
    {
        $this->examScheduleRepository->updateByIds($idExamSchedule, $inputs);
    }

    public function updateProctors (string $idExamSchedule, array $inputs)
    {
        $this->_formatUpdateProctorsInputs($inputs);
        $this->examScheduleRepository->syncPivot($idExamSchedule, $inputs, 'teachers');
    }

    private function _formatUpdateProctorsInputs (array &$inputs)
    {
        $values = [];
        foreach ($inputs as $key => $value)
        {
            $values[$value] = ['position' => $key + 1];
        }
        $inputs = $values;
    }
}