<?php

namespace App\Services;

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

    public function read (string $idTeacher)
    {
        return $this->teacherRepository->findByIds($idTeacher, ['*'], [],
                                                   [['with', 'department:id,name,id_faculty', 'department.faculty:id,name']]);
    }

    public function getSchedules (string $idTeacher, array $inputs)
    {
        return $this->scheduleDepository->findAllByIdTeacher($idTeacher, $inputs);
    }

    public function getExamSchedules (string $idTeacher, array $inputs)
    {
        return $this->examScheduleRepository->findByIdTeacher($idTeacher, $inputs);
    }

    public function getFixedSchedules (string $idTeacher, array $inputs)
    {
        return $this->fixedScheduleRepository->findByIdTeacher($idTeacher, $inputs);
    }

    public function getModuleClasses (string $idTeacher, array $inputs)
    {
        $inputs = $this->_formatInputs($inputs);
        return $this->moduleClassRepository->find(['id', 'name'],
                                                  [['id_teacher', '=', $idTeacher]], [], [],
                                                  [['filter', $inputs]]);
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
}