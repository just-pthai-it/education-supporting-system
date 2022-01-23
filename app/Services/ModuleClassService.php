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
        $this->moduleClassDepository->update(['id_teacher' => $id_teacher], [['id', 'in', $ids]]);
    }
}
