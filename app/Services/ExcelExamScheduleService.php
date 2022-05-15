<?php

namespace App\Services;

use App\Helpers\GFString;
use App\Helpers\GFunction;
use App\Imports\FileImport;

class ExcelExamScheduleService implements Contracts\ExcelServiceContract
{
    private array $teachers;
    private int $numericalOrderIndex = 0;
    private int $moduleIdIndex = -1;
    private int $moduleClassNameIndex = -1;
    private int $methodIndex = -1;
    private int $dateIndex = -1;
    private int $timeIndex = -1;
    private int $numberOfStudentsIndex = -1;
    private int $firstTeacherIndex = -1;
    private int $lastTeacherIndex = -1;
    private int $roomIndex1 = -1;
    private int $roomIndex2 = -1;

    public function readData ($file_name, ...$params) : array
    {
        $this->teachers = $params[0];
        $rawData        = $this->_getData($file_name);
        return $this->_formatData($rawData);
    }

    private function _getData ($file_name) : array
    {
        return (new FileImport())->toArray(storage_path('app/public/excels/') . $file_name);
    }

    private function _formatData ($rawData) : array
    {
        $examSchedules         = [];
        $examSchedulesTeachers = [];
        $examSchedulesRooms    = [];

        foreach ($rawData as $sheet)
        {
            $isStart = false;
            foreach ($sheet as $row)
            {
                if ($row[$this->numericalOrderIndex] == 'STT')
                {
                    $i = 0;
                    while (true)
                    {
                        switch (true)
                        {
                            case $row[$i] == 'Mã học phần':
                                $this->moduleIdIndex = $i;
                                break;

                            case $row[$i] == 'Lớp học phần':
                                $this->moduleClassNameIndex = $i;
                                break;

                            case $row[$i] == 'Hình thức thi':
                                $this->methodIndex = $i;
                                break;

                            case $row[$i] == 'Ngày thi':
                                $this->dateIndex = $i;
                                break;

                            case GFString::removeExtraSpace($row[$i]) == 'Ca thi (Giờ thi)':
                                $this->timeIndex = $i;
                                break;

                            case $row[$i] == 'Số SV':
                                $this->numberOfStudentsIndex = $i;
                                break;

                            case $row[$i] == 'Tên phòng':
                                $this->roomIndex1 = $i;
                                break;

                            case strpos($row[$i], 'GV') !== false:
                                if ($this->firstTeacherIndex == -1)
                                {
                                    $this->firstTeacherIndex = $i;
                                }
                                $this->lastTeacherIndex = $i;
                                break;

                            case $row[$i] == 'Phòng thi':
                                $this->roomIndex2 = $i;
                                break;
                        }

                        if ($row[$i] == 'Phòng thi')
                        {
                            break;
                        }

                        $i++;
                    }
                    $isStart = true;
                    continue;
                }

                if ($isStart)
                {
                    if (is_null($row[$this->numericalOrderIndex]))
                    {
                        break;
                    }

                    $idRoom = $row[$this->roomIndex2] == null
                        ? $row[$this->roomIndex1] : explode('.', $row[$this->roomIndex2])[1];
                    $idRoom = str_replace('Phòng thi TT', 'PTTT', $idRoom);

                    $idExamSchedule = GFunction::convertToIDModuleClass($row[$this->moduleIdIndex],
                                                                        $row[$this->moduleClassNameIndex]);

                    $this->_createExamSchedules($examSchedules, $idExamSchedule,
                                                $row[$this->methodIndex], $row[$this->dateIndex],
                                                $row[$this->timeIndex],
                                                $row[$this->numberOfStudentsIndex]);

                    $this->_createExamScheduleRoom($examSchedulesRooms, $idExamSchedule, $idRoom);

                    for ($j = $this->firstTeacherIndex; $j <= $this->lastTeacherIndex; $j++)
                    {
                        $this->_createExamSchedulesTeachers($examSchedulesTeachers,
                                                            $idExamSchedule, $row[$j] ?? '');
                    }
                }
            }
        }

        //         echo json_encode($examSchedules);
        return [
            'exam_schedules'          => $examSchedules,
            'exam_schedules_teachers' => $examSchedulesTeachers,
            'exam_schedules_rooms'    => $examSchedulesRooms,
        ];
    }

    private function _createExamSchedules (array  &$examSchedules, string $idModuleClass,
                                           string $method, string $date, string $time,
                                           string $numberOfStudents)
    {
        $dateTime = $this->_createDateTime($date, $time);

        $examSchedules[] = [
            'id'                 => $idModuleClass,
            'method'             => $method,
            'start_at'           => $dateTime[0],
            'end_at'             => $dateTime[1],
            'number_of_students' => $numberOfStudents,
        ];
    }

    private function _createExamSchedulesTeachers (array  &$examSchedulesTeaches,
                                                   string $idModuleClass, string $teacherName)
    {
        if (empty($teacherName))
        {
            return;
        }

        $examSchedulesTeaches[$idModuleClass][] = $this->teachers[$teacherName];
    }

    private function _createExamScheduleRoom (array  &$examSchedulesRoom, string $idExamSchedule,
                                              string $idRoom)
    {
        $examSchedulesRoom[$idExamSchedule] = explode(',', GFString::removeAllSpace($idRoom));
    }

    private function _createDateTime ($date, $time) : array
    {
        preg_replace('/\s+/', ' ', $time);
        if (is_numeric($date))
        {
            $date = GFunction::excelValueToDate($date);
        }
        else
        {
            $date = GFunction::formatDate($date);
        }

        $arr       = explode('-', $time);
        $timeStart = $date . ' ' . substr($arr[0], strlen($arr[0]) - 5) . ':00.000';
        $timeEnd   = $date . ' ' . substr($arr[1], 0, 5) . ':00.000';

        return [$timeStart, $timeEnd];
    }

    public function handleData ($formatted_data, ...$params)
    {

    }

    public function setParameters (...$parameters)
    {
        $this->teachers = $parameters[0];
    }
}