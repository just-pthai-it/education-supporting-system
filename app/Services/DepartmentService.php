<?php

namespace App\Services;

use App\Helpers\GFunction;
use App\Repositories\Contracts\TeacherRepositoryContract;
use App\Repositories\Contracts\ScheduleRepositoryContract;
use App\Repositories\Contracts\DepartmentRepositoryContract;
use App\Repositories\Contracts\ModuleClassRepositoryContract;
use App\Repositories\Contracts\ExamScheduleRepositoryContract;
use App\Repositories\Contracts\StudySessionRepositoryContract;
use App\Repositories\Contracts\FixedScheduleRepositoryContract;

class DepartmentService implements Contracts\DepartmentServiceContract
{
    private DepartmentRepositoryContract $departmentRepository;
    private FixedScheduleRepositoryContract $fixedScheduleRepository;
    private ExamScheduleRepositoryContract $examScheduleRepository;
    private ScheduleRepositoryContract $scheduleDepository;
    private ModuleClassRepositoryContract $moduleClassRepository;
    private StudySessionRepositoryContract $studySessionRepository;
    private TeacherRepositoryContract $teacherRepository;

    /**
     * @param DepartmentRepositoryContract    $departmentRepository
     * @param FixedScheduleRepositoryContract $fixedScheduleRepository
     * @param ExamScheduleRepositoryContract  $examScheduleRepository
     * @param ScheduleRepositoryContract      $scheduleDepository
     * @param ModuleClassRepositoryContract   $moduleClassRepository
     * @param StudySessionRepositoryContract  $studySessionRepository
     * @param TeacherRepositoryContract       $teacherRepository
     */
    public function __construct (DepartmentRepositoryContract    $departmentRepository,
                                 FixedScheduleRepositoryContract $fixedScheduleRepository,
                                 ExamScheduleRepositoryContract  $examScheduleRepository,
                                 ScheduleRepositoryContract      $scheduleDepository,
                                 ModuleClassRepositoryContract   $moduleClassRepository,
                                 StudySessionRepositoryContract  $studySessionRepository,
                                 TeacherRepositoryContract       $teacherRepository)
    {
        $this->departmentRepository    = $departmentRepository;
        $this->fixedScheduleRepository = $fixedScheduleRepository;
        $this->examScheduleRepository  = $examScheduleRepository;
        $this->scheduleDepository      = $scheduleDepository;
        $this->moduleClassRepository   = $moduleClassRepository;
        $this->studySessionRepository  = $studySessionRepository;
        $this->teacherRepository       = $teacherRepository;
    }

    public function getSchedules ($id_department, array $inputs)
    {
        return $this->scheduleDepository->findAllByIdDepartment($id_department, $inputs);
    }

    public function getExamSchedules ($id_department, array $inputs)
    {
        return $this->examScheduleRepository->findByIdDepartment($id_department, $inputs);
    }

    public function getFixedSchedules ($id_department, array $inputs)
    {
        return $this->fixedScheduleRepository->findByIdDepartment($id_department, $inputs);
    }

    public function getModuleClassesByStudySessions ($id_department, $term, $study_sessions)
    {
        $study_sessions    = GFunction::getOfficialStudySessions($term, $study_sessions);
        $id_study_sessions = $this->_getIdStudySessions($study_sessions);
        return $this->moduleClassRepository->findByIdDepartment($id_department, $id_study_sessions);
    }

    private function _getIdStudySessions (array $study_sessions)
    {
        return $this->studySessionRepository->pluck([['id']], [['name', 'in', $study_sessions]])
                                            ->toArray();
    }

    public function getTeachers ($id_department)
    {
        return $this->teacherRepository->find(['id', 'name'],
                                              [['id_department', '=', $id_department]]);
    }
}