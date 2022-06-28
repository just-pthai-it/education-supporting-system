<?php

namespace App\Services;

use Illuminate\Support\Arr;
use App\Http\Resources\ModuleClassResource;
use App\Repositories\Contracts\ModuleClassRepositoryContract;
use App\Repositories\Contracts\StudySessionRepositoryContract;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ModuleClassService implements Contracts\ModuleClassServiceContract
{
    private ModuleClassRepositoryContract $moduleClassRepository;
    private StudySessionRepositoryContract $studySessionRepository;

    /**
     * @param ModuleClassRepositoryContract  $moduleClassDepository
     * @param StudySessionRepositoryContract $studySessionRepository
     */
    public function __construct (ModuleClassRepositoryContract  $moduleClassDepository,
                                 StudySessionRepositoryContract $studySessionRepository)
    {
        $this->moduleClassRepository  = $moduleClassDepository;
        $this->studySessionRepository = $studySessionRepository;
    }

    public function readMany (array $inputs)
    {
        $inputs = $this->_formatInputs($inputs);
        return $this->moduleClassRepository->find(['id', 'name'], [], [], [],
                                                  [['filter', $inputs]]);
    }

    public function readManyByIdDepartment (string $idDepartment,
                                            array  $inputs) : AnonymousResourceCollection
    {
        $inputs        = $this->_formatInputs($inputs);
        $moduleClasses = $this->moduleClassRepository->findByIdDepartment($idDepartment, $inputs);
        return ModuleClassResource::collection($moduleClasses);
    }

    public function readManyByIdTeacher (string $idTeacher,
                                         array  $inputs) : AnonymousResourceCollection
    {
        $inputs        = $this->_formatInputs($inputs);
        $moduleClasses = $this->moduleClassRepository->find(['id', 'name'],
                                                            [['id_teacher', '=', $idTeacher]],
                                                            [], [], [['filter', $inputs]]);
        return ModuleClassResource::collection($moduleClasses);
    }

    private function _formatInputs (array $inputs) : array
    {
        if (isset($inputs['study_sessions']))
        {
            $idStudySessions = $this->_readIdStudySessionsByNames($inputs['study_sessions']);
            $idStudySessions = implode(',', $idStudySessions);;
            $inputs = array_merge($inputs, ['id_study_session' => ['in' => $idStudySessions]]);
        }

        return $inputs;
    }

    public function updateMany (array $inputs)
    {
        $this->moduleClassRepository->update(Arr::except($inputs, ['ids']),
                                             [['id', 'in', $inputs['ids']]]);
    }

    public function destroyMany (array $inputs)
    {
        $idStudySession = $this->_readIdStudySessionsByNames($inputs['study_session'])[0];
        $this->moduleClassRepository->softDeleteByIdDepartmentAndIdStudySession($inputs['id_department'],
                                                                                $idStudySession);
    }

    private function _readIdStudySessionsByNames (string $studySessions)
    {
        $studySessions = explode(',', $studySessions);
        return $this->studySessionRepository->pluck(['id'], [['name', 'in', $studySessions]])
                                            ->toArray();
    }
}
