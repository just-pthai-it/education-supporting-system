<?php

namespace App\Services;

use App\Repositories\Contracts\ExamScheduleRepositoryContract;

class ExamScheduleService implements Contracts\ExamScheduleServiceContract
{
    private ExamScheduleRepositoryContract $examScheduleRepository;

    /**
     * @param ExamScheduleRepositoryContract $examScheduleRepository
     */
    public function __construct (ExamScheduleRepositoryContract $examScheduleRepository)
    {
        $this->examScheduleRepository = $examScheduleRepository;
    }

    public function getTeacherExamSchedules ($id_teacher) : array
    {
        $exam_schedules = $this->examScheduleRepository->findAllByIdTeacher($id_teacher);
        return $this->_formatResponse($exam_schedules);
    }

    private function _formatResponse ($exam_schedules) : array
    {
        $response                 = [];
        $current_id_exam_schedule = '';
        $i                        = 0;
        foreach ($exam_schedules as $exam_schedule)
        {
            if ($exam_schedule['id_module_class'] != $current_id_exam_schedule)
            {
                $i++;
                $response[$i] = $exam_schedule;
                unset($response[$i]['teacher_name']);
                unset($response[$i]['position']);
                $response[$i]['teachers'][] = $exam_schedule['teacher_name'];

                $current_id_exam_schedule = $exam_schedule['id_module_class'];
            }
            else
            {
                $response[$i]['teachers'][] = $exam_schedule['teacher_name'];
            }
        }

        return array_values($response);
    }

    public function updateExamSchedule ($data)
    {
        $this->examScheduleRepository->update($data);
    }
}