<?php

namespace App\Services;

use App\Helpers\GFString;
use App\Services\Abstracts\AExcelService;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Reader\Exception\ReaderNotOpenedException;

class ExcelExamScheduleService extends AExcelService
{
    private int $numericalOrderIndex = 0;
    private int $moduleIdIndex = -1;
    private int $moduleClassNameIndex = -1;
    private int $methodIndex = -1;
    private int $dateIndex = -1;
    private int $timeIndex = -1;
    private int $numberOfStudentsIndex = -1;
    private int $roomIndex = -1;
    private int $numberOfRoomsIndex = -1;

    /**
     * @throws UnsupportedTypeException
     * @throws IOException
     * @throws ReaderNotOpenedException
     */
    public function readData (string $filePath, array $parameters = []) : array
    {
        $reader = $this->_getReader($filePath);

        $examSchedules = [];

        foreach ($reader->getSheetIterator() as $sheet)
        {
            $isStart = false;
            foreach ($sheet->getRowIterator() as $row)
            {
                $cells = $row->getCells();
                if (empty($cells))
                {
                    if ($isStart)
                    {
                        break;
                    }

                    continue;
                }

                if ($isStart && empty($this->_getCellData($cells, $this->numericalOrderIndex)))
                {
                    break;
                }

                if ($this->_getCellData($cells, $this->numericalOrderIndex) == 'STT')
                {
                    foreach ($cells as $i => $cell)
                    {
                        switch (true)
                        {
                            case $cell->getValue() == 'Mã học phần':
                                $this->moduleIdIndex = $i;
                                break;

                            case $cell->getValue() == 'Lớp học phần':
                                $this->moduleClassNameIndex = $i;
                                break;

                            case $cell->getValue() == 'Hình thức thi':
                                $this->methodIndex = $i;
                                break;

                            case $cell->getValue() == 'Ngày thi':
                                $this->dateIndex = $i;
                                break;

                            case GFString::removeExtraSpace($cell->getValue()) ==
                                 'Ca thi (Giờ thi)':
                                $this->timeIndex = $i;
                                break;

                            case $cell->getValue() == 'Số SV':
                                $this->numberOfStudentsIndex = $i;
                                break;

                            case GFString::removeExtraSpace(GFString::removeAllEndOfLine($cell->getValue())) ==
                                 'Số phòng':
                                $this->numberOfRoomsIndex = $i;
                                break;

                            case $cell->getValue() == 'Tên phòng':
                                $this->roomIndex = $i;
                                break;
                        }
                    }
                    $isStart = true;
                    continue;
                }

                if ($isStart)
                {
                    $rooms         = $this->_getCellData($cells, $this->roomIndex);
                    $numberOfRooms = $this->_getCellData($cells, $this->numberOfRoomsIndex);
                    $idRooms       = $this->_getIdRooms($rooms, $numberOfRooms);

                    $idModule        = $this->_getCellData($cells, $this->moduleIdIndex);
                    $moduleClassName = $this->_getCellData($cells, $this->moduleClassNameIndex);
                    $idModuleClass   = $this->_getIdModuleClass($idModule, $moduleClassName);

                    foreach ($idRooms as $idRoom)
                    {
                        $method = $this->_getCellData($cells, $this->methodIndex);
                        $date   = $this->_getCellData($cells, $this->dateIndex);
                        $time   = $this->_getCellData($cells, $this->timeIndex);;
                        $numberOfStudents = $this->_getCellData($cells,
                                                                $this->numberOfStudentsIndex);

                        $this->_createExamSchedules($examSchedules, $idModuleClass, $method, $date,
                                                    $time, $numberOfStudents, $idRoom);
                    }
                }
            }
        }

        return [
            'exam_schedules' => $examSchedules,
        ];
    }

    private function _getIdModuleClass (string $idModule, string $moduleClassName) : string
    {
        return $this->_convertToIdModuleClass($idModule, $moduleClassName);
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

    private function _createDateTime ($date, $time) : array
    {
        preg_replace('/\s+/', ' ', $time);
        $date = explode(' ', $date)[0];

        $arr       = explode('-', $time);
        $timeStart = $date . ' ' . substr($arr[0], strlen($arr[0]) - 5) . ':00.000';
        $timeEnd   = $date . ' ' . substr($arr[1], 0, 5) . ':00.000';

        return [$timeStart, $timeEnd];
    }
}