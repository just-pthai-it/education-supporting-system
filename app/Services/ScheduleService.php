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

    public function getTeacherSchedules ($id_teacher) : array
    {
        $study_sessions = $this->studySessionRepository->findAllRecent2();
        return $this->scheduleDepository->findAllByIdTeacher($id_teacher, $study_sessions);
    }

    public function getDepartmentSchedules ($id_department)
    {
        $study_sessions = $this->studySessionRepository->findAllRecent2();
        return $this->scheduleDepository->findAllByIdDepartment($id_department, $study_sessions);
    }
}
