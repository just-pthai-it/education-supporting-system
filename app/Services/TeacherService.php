<?php

namespace App\Services;

use App\Helpers\GFunction;
use App\Repositories\Contracts\TeacherRepositoryContract;
use App\Repositories\Contracts\ScheduleRepositoryContract;
use App\Repositories\Contracts\ModuleClassRepositoryContract;
use App\Repositories\Contracts\ExamScheduleRepositoryContract;
use App\Repositories\Contracts\StudySessionRepositoryContract;
use App\Repositories\Contracts\FixedScheduleRepositoryContract;

class TeacherService implements Contracts\TeacherServiceContract
{
    private TeacherRepositoryContract $teacherRepository;
    private FixedScheduleRepositoryContract $fixedScheduleRepository;
    private ExamScheduleRepositoryContract $examScheduleRepository;
    private ScheduleRepositoryContract $scheduleDepository;
    private ModuleClassRepositoryContract $moduleClassRepository;
    private StudySessionRepositoryContract $studySessionRepository;

    /**
     * @param TeacherRepositoryContract       $teacherRepository
     * @param FixedScheduleRepositoryContract $fixedScheduleRepository
     * @param ExamScheduleRepositoryContract  $examScheduleRepository
     * @param ScheduleRepositoryContract      $scheduleDepository
     * @param ModuleClassRepositoryContract   $moduleClassRepository
     * @param StudySessionRepositoryContract  $studySessionRepository
     */
    public function __construct (TeacherRepositoryContract       $teacherRepository,
                                 FixedScheduleRepositoryContract $fixedScheduleRepository,
                                 ExamScheduleRepositoryContract  $examScheduleRepository,
                                 ScheduleRepositoryContract      $scheduleDepository,
                                 ModuleClassRepositoryContract   $moduleClassRepository,
                                 StudySessionRepositoryContract  $studySessionRepository)
    {
        $this->teacherRepository       = $teacherRepository;
        $this->fixedScheduleRepository = $fixedScheduleRepository;
        $this->examScheduleRepository  = $examScheduleRepository;
        $this->scheduleDepository      = $scheduleDepository;
        $this->moduleClassRepository   = $moduleClassRepository;
        $this->studySessionRepository  = $studySessionRepository;
    }

    public function getById ($id_teacher)
    {
        return $this->teacherRepository->findByIds($id_teacher, ['*'], [],
                                                   [['with', 'department:id,name,id_faculty', 'department.faculty:id,name']]);
    }

    public function getSchedules ($id_teacher, array $inputs)
    {
        return $this->scheduleDepository->findAllByIdTeacher($id_teacher, $inputs);
    }

    public function getExamSchedules ($id_teacher, array $inputs)
    {
        return $this->examScheduleRepository->findByIdTeacher($id_teacher, $inputs);
    }

    public function getFixedSchedules ($id_teacher, array $inputs)
    {
        return $this->fixedScheduleRepository->findByIdTeacher($id_teacher, $inputs);
    }

    public function getModuleClasses ($id_teacher, array $inputs)
    {
        $inputs = $this->_formatInputs($inputs);
        return $this->moduleClassRepository->find(['id', 'name'],
                                                  [['id_teacher', '=', $id_teacher]], [], [],
                                                  [['filter', $inputs]]);
    }

    private function _formatInputs (array $inputs) : array
    {
        if (isset($inputs['study_sessions']))
        {
            $id_study_sessions = $this->_getIdStudySessions($inputs['study_sessions']);;
            $inputs = array_merge($inputs, ['id_study_session' => ['in' => $id_study_sessions]]);
        }

        return $inputs;
    }

    private function _getIdStudySessions (string $study_sessions) : string
    {
        $id_study_sessions = $this->studySessionRepository->pluck([['id']],
                                                                  [['name', 'in',
                                                                    explode(',', $study_sessions)]])
                                                          ->toArray();
        return implode(',', $id_study_sessions);
    }
}