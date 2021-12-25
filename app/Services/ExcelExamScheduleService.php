<?php

namespace App\Services;

use App\Helpers\GFunction;
use App\Imports\FileImport;

class ExcelExamScheduleService implements Contracts\ExcelServiceContract
{
    private $teachers;

    public function readData ($file_name, ...$params) : array
    {
        $raw_data = $this->_getData($file_name);
        return $this->_formatData($raw_data);
    }

    private function _getData ($file_name) : array
    {
        return (new FileImport())->toArray(storage_path('app/public/excels/') . $file_name);
    }

    private function _formatData ($raw_data) : array
    {
        $first_teacher_index = -1;
        $last_teacher_index  = -1;

        $exam_schedules          = [];
        $exam_schedules_teachers = [];
        foreach ($raw_data as $sheet)
        {
            $flag = false;
            foreach ($sheet as $row)
            {
                if ($row[0] == 'STT')
                {
                    for ($i = 5; ; $i++)
                    {
                        if (strpos($row[$i], 'GV') !== false)
                        {
                            $first_teacher_index =
                                $first_teacher_index == -1 ? $i : $first_teacher_index;

                            $last_teacher_index = $i;
                        }
                        else if ($last_teacher_index != -1)
                        {
                            break;
                        }
                    }
                    $flag = true;
                    continue;
                }

                if ($flag)
                {
                    if (is_null($row[0]))
                    {
                        break;
                    }


                    $id_room = $row[$last_teacher_index + 1] ==
                               null ? $row[11] : substr($row[$last_teacher_index + 1], 2);
                    $id_room = str_replace('Phòng thi TT', 'PTTT', $id_room);

                    $id_module_class = GFunction::convertToIDModuleClass($row[1], $row[3]);

                    $this->_createExamSchedules($exam_schedules, $id_module_class, $row[6], $row[7],
                                                $row[8], $id_room);

                    for ($j = $first_teacher_index; $j <= $last_teacher_index; $j++)
                    {
                        $this->_createExamSchedulesTeachers($exam_schedules_teachers,
                                                            $id_module_class, $row[$j],
                                                            $j - $first_teacher_index + 1);
                    }

                }
            }
        }

        return [
            'exam_schedules'          => $exam_schedules,
            'exam_schedules_teachers' => $exam_schedules_teachers
        ];
    }

    private function _createExamSchedules (&$exam_schedules, $id_module_class,
                                           $method, $date, $time, $id_room)
    {
        $date_time = $this->_createDateTime($date, $time);

        $exam_schedules[] = [
            'id_module_class' => $id_module_class,
            'method'          => $method,
            'time_start'      => $date_time[0],
            'time_end'        => $date_time[1],
            'id_room'         => $id_room,
        ];
    }

    private function _createExamSchedulesTeachers (&$exam_schedules_teaches, $id_module_class,
                                                   $teacher_name, $position)
    {
        $id_teacher = $this->teachers[$teacher_name];
        $exam_schedules_teaches[$id_module_class][$id_teacher] = ['position' => $position];
    }

    private function _createDateTime ($date, $time) : array
    {
        preg_replace('/\s+/', ' ', $time);
        $date       = GFunction::excelValueToDate($date);
        $arr        = explode('-', $time);
        $time_start = $date . ' ' . substr($arr[0], strlen($arr[0]) - 5) . ':00.000';
        $time_end   = $date . ' ' . substr($arr[1], 0, 5) . ':00.000';

        return [$time_start, $time_end];
    }

    public function handleData ($formatted_data, ...$params)
    {

    }

    public function setParameters (...$parameters)
    {
        $this->teachers = $parameters[0];
    }
}