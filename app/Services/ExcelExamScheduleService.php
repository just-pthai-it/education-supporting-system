<?php

namespace App\Services;

use App\Helpers\GFString;
use App\Helpers\GFunction;
use App\Imports\FileImport;

class ExcelExamScheduleService implements Contracts\ExcelServiceContract
{
    //    private array $teachers;
    private int $numericalOrderIndex = 0;
    private int $moduleIdIndex = -1;
    private int $moduleClassNameIndex = -1;
    private int $methodIndex = -1;
    private int $dateIndex = -1;
    private int $timeIndex = -1;
    private int $numberOfStudentsIndex = -1;
    //    private int $firstTeacherIndex = -1;
    //    private int $lastTeacherIndex = -1;
    private int $roomIndex = -1;
    private int $numberOfRoomsIndex = -1;

    public function readData ($file_name, ...$params) : array
    {
        //        $this->teachers = $params[0];
        $rawData = $this->_getData($file_name);
        return $this->_formatData($rawData);
    }

    private function _getData ($file_name) : array
    {
        return (new FileImport())->toArray(storage_path('app/public/excels/') . $file_name);
    }

    private function _formatData ($rawData) : array
    {
        $examSchedules = [];
        //        $examSchedulesTeachers = [];

        foreach ($rawData as $sheet)
        {
            $isStart = false;
            foreach ($sheet as $row)
            {
                if ($row[$this->numericalOrderIndex] == 'STT')
                {
                    foreach ($row as $i => $column)
                    {
                        switch (true)
                        {
                            case $column == 'Mã học phần':
                                $this->moduleIdIndex = $i;
                                break;

                            case $column == 'Lớp học phần':
                                $this->moduleClassNameIndex = $i;
                                break;

                            case $column == 'Hình thức thi':
                                $this->methodIndex = $i;
                                break;

                            case $column == 'Ngày thi':
                                $this->dateIndex = $i;
                                break;

                            case GFString::removeExtraSpace($column) == 'Ca thi (Giờ thi)':
                                $this->timeIndex = $i;
                                break;

                            case $column == 'Số SV':
                                $this->numberOfStudentsIndex = $i;
                                break;

                            case GFString::removeExtraSpace(GFString::removeAllEndOfLine($column)) ==
                                 'Số phòng':
                                $this->numberOfRoomsIndex = $i;
                                break;

                            case $column == 'Tên phòng':
                                $this->roomIndex = $i;
                                break;

                            //                            case strpos($column, 'GV') !== false:
                            //                                if ($this->firstTeacherIndex == -1)
                            //                                {
                            //                                    $this->firstTeacherIndex = $i;
                            //                                }
                            //                                $this->lastTeacherIndex = $i;
                            //                                break;
                        }
                    }
                    //                    while (true)
                    //                    {
                    //                        switch (true)
                    //                        {
                    //                            case $row[$i] == 'Mã học phần':
                    //                                $this->moduleIdIndex = $i;
                    //                                break;
                    //
                    //                            case $row[$i] == 'Lớp học phần':
                    //                                $this->moduleClassNameIndex = $i;
                    //                                break;
                    //
                    //                            case $row[$i] == 'Hình thức thi':
                    //                                $this->methodIndex = $i;
                    //                                break;
                    //
                    //                            case $row[$i] == 'Ngày thi':
                    //                                $this->dateIndex = $i;
                    //                                break;
                    //
                    //                            case GFString::removeExtraSpace($row[$i]) == 'Ca thi (Giờ thi)':
                    //                                $this->timeIndex = $i;
                    //                                break;
                    //
                    //                            case $row[$i] == 'Số SV':
                    //                                $this->numberOfStudentsIndex = $i;
                    //                                break;
                    //
                    //                            case GFString::removeExtraSpace(GFString::removeAllEndOfLine($row[$i])) ==
                    //                                 'Số phòng':
                    //                                $this->numberOfRoomsIndex = $i;
                    //                                break;
                    //
                    //                            case $row[$i] == 'Tên phòng':
                    //                                $this->roomIndex = $i;
                    //                                break;
                    //
                    //                            case strpos($row[$i], 'GV') !== false:
                    //                                if ($this->firstTeacherIndex == -1)
                    //                                {
                    //                                    $this->firstTeacherIndex = $i;
                    //                                }
                    //                                $this->lastTeacherIndex = $i;
                    //                                break;
                    //
                    //                            case $row[$i] == 'Phòng thi':
                    //                                $this->roomIndex2 = $i;
                    //                                break;
                    //                        }
                    //
                    //                        if ($row[$i] == 'Phòng thi')
                    //                        {
                    //                            break;
                    //                        }
                    //
                    //                        $i++;
                    //                    }
                    $isStart = true;
                    continue;
                }

                if ($isStart)
                {
                    if (is_null($row[$this->numericalOrderIndex]))
                    {
                        break;
                    }

                    $idRooms       = $this->_getIdRooms($row[$this->roomIndex] ?? '',
                                                        $row[$this->numberOfRoomsIndex]);
                    $idModuleClass = $this->_getIdModuleClass($row[$this->moduleIdIndex],
                                                              $row[$this->moduleClassNameIndex]);
                    //                    $idTeachers    = $this->_getIdTeachers($row);

                    foreach ($idRooms as $idRoom)
                    {
                        $this->_createExamSchedules($examSchedules, $idModuleClass,
                                                    $row[$this->methodIndex],
                                                    $row[$this->dateIndex],
                                                    $row[$this->timeIndex],
                                                    $row[$this->numberOfStudentsIndex], $idRoom);
                    }

                    //                    $this->_createExamSchedulesTeachers($examSchedulesTeachers, $idTeachers);
                }
            }
        }

        return [
            'exam_schedules' => $examSchedules,
            //            'exam_schedules_teachers' => $examSchedulesTeachers,
        ];
    }

    private function _getIdModuleClass (string $idModule, string $moduleClassName) : string
    {
        return GFunction::convertToIDModuleClass($idModule, $moduleClassName);
    }

    private function _getIdRooms (string $room, string $numberOfRooms)
    {
        $room = GFString::removeAllSpace(GFString::removeAllEndOfLine($room));
        if ($room == '')
        {
            return array_fill(0, intval($numberOfRooms), null);
        }

        $idRooms = explode('.', $room);
        $idRooms = $idRooms[1] ?? $idRooms[0];
        $idRooms = str_replace('PhòngthiTT', 'PTTT', $idRooms);
        $idRooms = explode(',', $idRooms);
        if (count($idRooms) < intval($numberOfRooms))
        {
            for ($i = count($idRooms) + 1; $i <= intval($numberOfRooms); $i++)
            {
                $idRooms[] = null;
            }
        }

        return $idRooms;
    }

    //    private function _getIdTeachers (array &$row) : array
    //    {
    //        $idTeachers = [];
    //        for ($j = $this->firstTeacherIndex; $j <= $this->lastTeacherIndex; $j++)
    //        {
    //            if (is_null($row[$j]))
    //            {
    //                continue;
    //            }
    //
    //            $idTeachers[] = $this->teachers[$row[$j]];
    //        }
    //        return $idTeachers;
    //    }

    private function _createExamSchedules (array  &$examSchedules, string $idModuleClass,
                                           string $method, string $date, string $time,
                                           string $numberOfStudents, $idRoom)
    {
        $dateTime = $this->_createDateTime($date, $time);

        $examSchedules[] = [
            'id_module_class'    => $idModuleClass,
            'method'             => $method,
            'start_at'           => $dateTime[0],
            'end_at'             => $dateTime[1],
            'number_of_students' => $numberOfStudents,
            'id_room'            => $idRoom,
        ];
    }

    //    private function _createExamSchedulesTeachers (array &$examSchedulesTeaches, array $idTeachers)
    //    {
    //        if (empty($idTeachers))
    //        {
    //            return;
    //        }
    //
    //        $examSchedulesTeaches[] = $idTeachers;
    //    }

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