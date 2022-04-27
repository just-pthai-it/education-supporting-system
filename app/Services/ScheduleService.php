<?php

namespace App\Services;

use App\Http\Resources\ScheduleResource;
use App\Repositories\Contracts\TeacherRepositoryContract;
use App\Repositories\Contracts\ScheduleRepositoryContract;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ScheduleService implements Contracts\ScheduleServiceContract
{
    private ScheduleRepositoryContract $scheduleDepository;
    private TeacherRepositoryContract $teacherRepository;

    /**
     * @param ScheduleRepositoryContract $scheduleDepository
     * @param TeacherRepositoryContract  $teacherRepository
     */
    public function __construct (ScheduleRepositoryContract $scheduleDepository,
                                 TeacherRepositoryContract  $teacherRepository)
    {
        $this->scheduleDepository = $scheduleDepository;
        $this->teacherRepository  = $teacherRepository;
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
        $schedules = $this->scheduleDepository->findAllByIdTeachers($idTeacher, $inputs);
        return ScheduleResource::collection($schedules);
    }

    public function update (string $idSchedule, array $scheduleArr)
    {
        $this->scheduleDepository->updateByIds($idSchedule, $scheduleArr);
    }
}
