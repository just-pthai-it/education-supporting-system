<?php

namespace App\Services;

use App\Helpers\GFunction;
use App\Imports\FileImport;

class ExamScheduleExcelService implements Contracts\ExcelServiceContract
{
    private array $teachers;

    public function readData ($file_name) : array
    {
        $raw_data = $this->_getData($file_name);
        return $this->_formatData($raw_data);
    }

    private function _getData ($file_name) : array
    {
        $raw_data = (new FileImport())->toArray(storage_path('app/public/excels/') . $file_name);
        return $raw_data;
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

                    $teacher_names = [];
                    for ($j = $first_teacher_index; $j <= $last_teacher_index; $j++)
                    {
                        $teacher_names[] = $row[$j];
                    }

                    $id_room = $row[$last_teacher_index + 1] ==
                               null ? $row[11] : substr($row[$last_teacher_index + 1], 2);
                    $id_room = str_replace('Phòng thi TT', 'PTTT', $id_room);

                    $id_module_class = GFunction::convertToIDModuleClass($row[1], $row[3]);

                    $this->_createExamSchedules($exam_schedules, $id_module_class, $row[6], $row[7],
                                                $row[8], $id_room);
                    $this->_createExamSchedulesTeachers($exam_schedules_teachers,
                                                        $id_module_class, $teacher_names);
                }
            }
        }

        return [
            'exam_schedules'          => $exam_schedules,
            'exam_schedules_teachers' => $exam_schedules_teachers
        ];
    }

    private function _createExamSchedules (&$exam_schedules, $id_module_class,
                                           $method, $date_start, $time_start, $id_room)
    {
        $exam_schedules[] = [
            'id_module_class' => $id_module_class,
            'method'          => $method,
            'date_start'      => GFunction::excelValueToDate($date_start),
            'time_start'      => preg_replace('/\s+/', ' ', $time_start),
            'id_room'         => $id_room,
        ];
    }

    private function _createExamSchedulesTeachers (&$exam_schedules_teaches, $id_module_class,
                                                   $teacher_names)
    {
        foreach ($teacher_names as $teacher_name)
        {
            $exam_schedules_teaches[$id_module_class][] = $this->teachers[$teacher_name];
        }
    }

    public function handleData ($formatted_data)
    {

    }

    public function setParameters (...$parameters)
    {
        $this->teachers = $parameters[0];
    }
}