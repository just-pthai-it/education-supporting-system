<?php

namespace App\Services;

use Illuminate\Support\Arr;
use App\Http\Resources\ExamScheduleResource;
use App\Repositories\Contracts\ExamScheduleRepositoryContract;
use App\Repositories\Contracts\StudySessionRepositoryContract;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ExamScheduleService implements Contracts\ExamScheduleServiceContract
{
    private ExamScheduleRepositoryContract $examScheduleRepository;
    private StudySessionRepositoryContract $studySessionRepository;

    /**
     * @param ExamScheduleRepositoryContract $examScheduleRepository
     * @param StudySessionRepositoryContract $studySessionRepository
     */
    public function __construct (ExamScheduleRepositoryContract $examScheduleRepository,
                                 StudySessionRepositoryContract $studySessionRepository)
    {
        $this->examScheduleRepository = $examScheduleRepository;
        $this->studySessionRepository = $studySessionRepository;
    }

    public function readManyByIdDepartment (string $idDepartment,
                                            array  $inputs) : AnonymousResourceCollection
    {
        $this->_formatStudySessionInput($inputs);
        $examSchedules = $this->examScheduleRepository->findByIdDepartment($idDepartment, $inputs);
        return ExamScheduleResource::collection($examSchedules);
    }

    public function readManyByIdTeacher (string $idTeacher,
                                         array  $inputs) : AnonymousResourceCollection
    {
        $this->_formatStudySessionInput($inputs);
        $examSchedules = $this->examScheduleRepository->findByIdTeacher($idTeacher, $inputs);
        return ExamScheduleResource::collection($examSchedules);
    }

    private function _formatStudySessionInput (array &$inputs)
    {
        $idStudySession = $this->_readIdStudySessionByName($inputs['study_session']);;
        $inputs['id_study_session'] = $idStudySession;
        unset($inputs['study_session']);
    }

    private function _readIdStudySessionByName (string $studySession)
    {
        return $this->studySessionRepository->find(['id'], [['name', '=', $studySession]])[0]->id;
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

    public function createExamScheduleTeacher (string $idExamSchedule, array $inputs)
    {
        $this->_formatProctorsInputs($inputs);
        $this->examScheduleRepository->syncPivot($idExamSchedule, $inputs, 'teachers');
    }

    private function _formatProctorsInputs (array &$inputs)
    {
        $values = [];
        foreach ($inputs as $key => $value)
        {
            $values[$value] = ['position' => $key + 1];
        }
        $inputs = $values;
    }
}