<?php

namespace App\Services;

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

    public function getModuleClasses ($id_department, array $inputs)
    {
        $inputs = $this->_formatInputs($inputs);
        return $this->moduleClassRepository->findByIdDepartment($id_department, $inputs);
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

    private function _readIdStudySessionsByNames (string $studySessions)
    {
        $studySessions = explode(',', $studySessions);
        return $this->studySessionRepository->pluck(['id'], [['name', 'in', $studySessions]])
                                            ->toArray();
    }

    public function getTeachers (string $id_department, array $inputs)
    {
        return $this->teacherRepository->find(['id', 'name'],
                                              [['id_department', '=', $id_department]], [], [],
                                              [['filter', $inputs]]);
    }

    public function destroyModuleClassesByStudySession (string $idDepartment,
                                                        string $studySession)
    {
        $idStudySession = $this->_readIdStudySessionsByNames($studySession)[0];
        $this->moduleClassRepository->softDeleteByIdDepartmentAndIdStudySession($idDepartment,
                                                                                $idStudySession);
    }
}