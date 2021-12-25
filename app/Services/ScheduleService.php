<?php

namespace App\Services;

use App\Helpers\GFunction;
use App\Repositories\Contracts\ScheduleRepositoryContract;
use App\Repositories\Contracts\StudySessionRepositoryContract;
use App\Repositories\StudySessionRepository;

class ScheduleService implements Contracts\ScheduleServiceContract
{
    private ScheduleRepositoryContract $scheduleDepository;
    private StudySessionRepositoryContract $studySessionRepository;

    /**
     * @param ScheduleRepositoryContract $scheduleDepository
     * @param StudySessionRepository     $studySessionRepository
     */
    public function __construct (ScheduleRepositoryContract $scheduleDepository,
                                 StudySessionRepository     $studySessionRepository)
    {
        $this->scheduleDepository     = $scheduleDepository;
        $this->studySessionRepository = $studySessionRepository;
    }

    public function getTeacherSchedules ($id_teacher, $term, $study_sessions) : array
    {
        $study_sessions    = GFunction::getOfficialStudySessions($term, $study_sessions);
        $id_study_sessions = $this->studySessionRepository->findByNames($study_sessions);
        return $this->scheduleDepository->findAllByIdTeacher($id_teacher, $id_study_sessions);
    }

    public function getDepartmentSchedules ($id_department, $term, $study_sessions)
    {
        $study_sessions    = GFunction::getOfficialStudySessions($term, $study_sessions);
        $id_study_sessions = $this->studySessionRepository->findByNames($study_sessions);
        return $this->scheduleDepository->findAllByIdDepartment($id_department, $id_study_sessions);
    }
}
