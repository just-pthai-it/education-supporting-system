<?php

namespace App\Services;

use App\Http\Resources\ScheduleResource;
use App\Repositories\Contracts\TeacherRepositoryContract;
use App\Repositories\Contracts\ScheduleRepositoryContract;
use App\Repositories\Contracts\StudySessionRepositoryContract;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ScheduleService implements Contracts\ScheduleServiceContract
{
    private ScheduleRepositoryContract $scheduleDepository;
    private TeacherRepositoryContract $teacherRepository;
    private StudySessionRepositoryContract $studySessionRepository;

    /**
     * @param ScheduleRepositoryContract     $scheduleDepository
     * @param TeacherRepositoryContract      $teacherRepository
     * @param StudySessionRepositoryContract $studySessionRepository
     */
    public function __construct (ScheduleRepositoryContract     $scheduleDepository,
                                 TeacherRepositoryContract      $teacherRepository,
                                 StudySessionRepositoryContract $studySessionRepository)
    {
        $this->scheduleDepository     = $scheduleDepository;
        $this->teacherRepository      = $teacherRepository;
        $this->studySessionRepository = $studySessionRepository;
    }

    public function readManyByIdDepartment (string $idDepartment, string $relation,
                                            array  $inputs) : AnonymousResourceCollection
    {
        $schedules = [];
        switch ($relation)
        {
            case 'modules':
                $schedules = $this->scheduleDepository->findAllByIdDepartment($idDepartment,
                                                                              $inputs);
                break;
            case 'teachers';
                $idTeachers = $this->_readManyIdTeachersByIdDepartment($idDepartment);
                $schedules  = $this->scheduleDepository->findAllByIdTeachers($idTeachers, $inputs);
        }

        return ScheduleResource::collection($schedules);
    }

    private function _readManyIdTeachersByIdDepartment (string $idDepartment)
    {
        return $this->teacherRepository->pluck(['id'],
                                               [['id_department', '=', $idDepartment,
                                                 ['is_active', '=', 1]]])->toArray();
    }

    public function readManyByIdTeacher (string $idTeacher,
                                         array  $inputs) : AnonymousResourceCollection
    {
        $this->_formatInputs($inputs);
        $schedules = $this->scheduleDepository->findAllByIdTeachers([$idTeacher], $inputs);
        return ScheduleResource::collection($schedules);
    }

    private function _formatInputs (array &$inputs)
    {
        if (isset($inputs['study_session']))
        {
            $idStudySession = $this->_readIdStudySessionByName($inputs['study_session']);;
            $inputs['id_study_session'] = $idStudySession;
            unset($inputs['study_session']);
        }
    }

    private function _readIdStudySessionByName (string $studySession)
    {
        return $this->studySessionRepository->pluck(['id'], [['name', '=', $studySession]])
                                            ->toArray()[0];
    }

    public function update (string $idSchedule, array $scheduleArr)
    {
        $this->scheduleDepository->updateByIds($idSchedule, $scheduleArr);
    }
}
