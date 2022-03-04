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

    public function get ($id_teacher)
    {
        return $this->teacherRepository->findByIds($id_teacher, ['*'], [],
                                                   [['with', 'department:id,name,id_faculty', 'department.faculty:id,name']]);
    }

    public function getSchedules ($id_teacher, array $inputs)
    {
        return $this->scheduleDepository->findAllByIdTeacher($id_teacher, $inputs);
    }

    public function getExamSchedulesByDate ($id_teacher, $start, $end)
    {
        return $this->examScheduleRepository->findByIdTeacher($id_teacher, $start, $end);
    }

    public function getFixedSchedulesByStatus ($id_teacher, array $inputs)
    {
        return $this->fixedScheduleRepository->findByIdTeacher($id_teacher, $inputs);
    }

    public function getModuleClassesByStudySessions ($id_teacher, $term, $study_sessions)
    {
        $study_sessions    = GFunction::getOfficialStudySessions($term, $study_sessions);
        $id_study_sessions = $this->_getIdStudySessions($study_sessions);
        return $this->moduleClassRepository->find(['id as id_module_class', 'name as module_class_name'],
                                                  [['id_study_session', 'in', $id_study_sessions],
                                                   ['id_teacher', '=', $id_teacher]]);
    }

    private function _getIdStudySessions (array $study_sessions)
    {
        return $this->studySessionRepository->pluck([['id']], [['name', 'in', $study_sessions]])
                                            ->toArray();
    }
}