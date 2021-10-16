<?php


namespace App\Services;


use App\Repositories\Contracts\DataVersionStudentRepositoryContract;
use App\Repositories\Contracts\DataVersionTeacherRepositoryContract;
use App\Repositories\Contracts\ScheduleRepositoryContract;

class ScheduleService implements Contracts\ScheduleServiceContract
{
    private ScheduleRepositoryContract $scheduleDepository;
    private DataVersionStudentRepositoryContract $dataVersionStudentDepository;
    private DataVersionTeacherRepositoryContract $dataVersionTeacherDepository;

    /**
     * @param ScheduleRepositoryContract $scheduleDepository
     * @param DataVersionStudentRepositoryContract $dataVersionStudentDepository
     * @param DataVersionTeacherRepositoryContract $dataVersionTeacherDepository
     */
    public function __construct (ScheduleRepositoryContract           $scheduleDepository,
                                 DataVersionStudentRepositoryContract $dataVersionStudentDepository,
                                 DataVersionTeacherRepositoryContract $dataVersionTeacherDepository)
    {
        $this->scheduleDepository           = $scheduleDepository;
        $this->dataVersionStudentDepository = $dataVersionStudentDepository;
        $this->dataVersionTeacherDepository = $dataVersionTeacherDepository;
    }

    public function getStudentSchedules ($id_student) : array
    {
        $data         = $this->scheduleDepository->getSchedules1($id_student);
        $data_version = $this->dataVersionStudentDepository->getSingleColumn2($id_student, 'schedule');

        return [
            'data'         => $data,
            'data_version' => $data_version
        ];
    }

    public function getTeacherSchedules ($id_teacher) : array
    {
        $data         = $this->scheduleDepository->getSchedules2($id_teacher);
        $data_version = $this->dataVersionTeacherDepository->getSingleColumn2($id_teacher, 'schedule');

        return [
            'data'         => $data,
            'data_version' => $data_version
        ];
    }
}
