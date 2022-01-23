<?php

namespace App\Services;

use App\Helpers\GFunction;
use App\Repositories\Contracts\TeacherRepositoryContract;
use App\Repositories\Contracts\ModuleClassRepositoryContract;
use App\Repositories\Contracts\StudySessionRepositoryContract;

class ModuleClassService implements Contracts\ModuleClassServiceContract
{
    private ModuleClassRepositoryContract $moduleClassDepository;
    private TeacherRepositoryContract $teacherRepository;
    private StudySessionRepositoryContract $studySessionRepository;

    /**
     * @param ModuleClassRepositoryContract  $moduleClassDepository
     * @param TeacherRepositoryContract      $teacherRepository
     * @param StudySessionRepositoryContract $studySessionRepository
     */
    public function __construct (ModuleClassRepositoryContract  $moduleClassDepository,
                                 TeacherRepositoryContract      $teacherRepository,
                                 StudySessionRepositoryContract $studySessionRepository)
    {
        $this->moduleClassDepository  = $moduleClassDepository;
        $this->teacherRepository      = $teacherRepository;
        $this->studySessionRepository = $studySessionRepository;
    }

    public function updateModuleClass ($ids, $id_teacher)
    {
        foreach ($ids as $id)
        {
            $module_class = [
                'id'         => $id,
                'id_teacher' => $id_teacher
            ];
            $this->moduleClassDepository->update($module_class);
        }
    }

    public function getModuleClassesByIdTeacher ($id_teacher, $term, $study_sessions)
    {
        $study_sessions    = GFunction::getOfficialStudySessions($term, $study_sessions);
        $id_study_sessions = $this->_getIdStudySessions($study_sessions);
        return $this->moduleClassDepository->findByIdTeacher([$id_teacher], $id_study_sessions);
    }

    public function getModuleClassesByIdDepartment ($id_department, $term, $study_sessions)
    {
        $study_sessions    = GFunction::getOfficialStudySessions($term, $study_sessions);
        $id_study_sessions = $this->_getIdStudySessions($study_sessions);
        return $this->moduleClassDepository->findByIdDepartment($id_department, $id_study_sessions);
    }

    private function _getIdStudySessions (array $study_sessions)
    {
        return $this->studySessionRepository->pluck([['id']], [['name', 'in', $study_sessions]])
                                            ->toArray();
    }
}
