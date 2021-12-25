<?php

namespace App\Services;

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
        $study_sessions    = $this->_getOfficialStudySessions($term, $study_sessions);
        $id_study_sessions = $this->studySessionRepository->findByNames($study_sessions);
        return $this->scheduleDepository->findAllByIdTeacher($id_teacher, $id_study_sessions);
    }

    public function getDepartmentSchedules ($id_department, $term, $study_sessions)
    {
        $study_sessions    = $this->_getOfficialStudySessions($term, $study_sessions);
        $id_study_sessions = $this->studySessionRepository->findByNames($study_sessions);
        return $this->scheduleDepository->findAllByIdDepartment($id_department, $id_study_sessions);
    }

    private function _getOfficialStudySessions ($term, $study_sessions)
    {
        $study_sessions = explode(',', $study_sessions);
        foreach ($study_sessions as &$study_session)
        {
            $study_session = $term . '_' . $study_session;
        }

        return $study_sessions;
    }
}
