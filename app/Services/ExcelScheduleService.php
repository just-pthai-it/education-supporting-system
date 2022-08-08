<?php

namespace App\Services;

use Exception;
use App\Helpers\GData;
use App\Services\Abstracts\AExcelService;
use function App\Helpers\convertDateTime;
use function App\Helpers\calculateDatetime;

class ExcelScheduleService extends AExcelService
{
    private int   $idStudySession;
    private int   $numericalOrderIndex          = 1;
    private int   $idModuleIndex                = -1;
    private int   $moduleClassNameIndex         = -1;
    private int   $numberOfStudentsIndex        = -1;
    private int   $numberOfStudentsRealityIndex = -1;
    private int   $classTypeIndex               = -1;
    private int   $dateIndex                    = -1;
    private int   $numberOfWeeks                = -1;
    private array $periods                      = [];
    private array $roomsIndex                   = [];
    private int   $academicYearIndex            = -1;

    private const WORK_DAYS_IN_WEEK = 6;

    /**
     * @param string $filePath *
     *
     * @throws Exception
     */
    public function readData (string $filePath, array $parameters = []) : array
    {
        $this->idStudySession = $parameters['id_study_session'];

        $reader = $this->_getReader($filePath);

        $schedules     = [];
        $moduleClasses = [];

        foreach ($reader->getSheetIterator() as $sheet)
        {
            $this->__resetIndex();
            $idModule                = '';
            $moduleClassName         = '';
            $numberOfStudents        = '';
            $numberOfStudentsReality = '';
            $classType               = '';
            $dateRange               = '';
            $numberOfWeeks           = '';

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

                if ($this->_getCellData($cells, $this->numericalOrderIndex) == 'STT')
                {
                    foreach ($cells as $i => $cell)
                    {
                        if ($cell->getValue() == 'Mã học phần')
                        {
                            $this->idModuleIndex = $i;
                            continue;
                        }

                        if ($cell->getValue() == 'Lớp môn tín chỉ')
                        {
                            $this->moduleClassNameIndex = $i;
                            continue;
                        }

                        if ($cell->getValue() == 'Số SV DK')
                        {
                            $this->numberOfStudentsIndex = $i;
                            continue;
                        }

                        if ($cell->getValue() == 'Số SV ĐK')
                        {
                            $this->numberOfStudentsRealityIndex = $i;
                            continue;
                        }

                        if ($cell->getValue() == 'Kiểu học')
                        {
                            $this->classTypeIndex = $i;
                            continue;
                        }
                        if ($cell->getValue() == 'Thời gian')
                        {
                            $this->dateIndex = $i;
                            continue;
                        }

                        if ($cell->getValue() == 'Số tuần')
                        {
                            $this->numberOfWeeks = $i;
                            continue;
                        }

                        if ($cell->getValue() == 'Thứ 2')
                        {
                            for ($j = 0; $j < self::WORK_DAYS_IN_WEEK; $j++)
                            {
                                $this->periods[]    = $i + ($j * 2);
                                $this->roomsIndex[] = $i + ($j * 2) + 1;
                            }
                            continue;
                        }

                        if ($cell->getValue() == 'Khóa')
                        {
                            $this->academicYearIndex = $i;
                            break;
                        }
                    }

                    continue;
                }

                if ($isStart)
                {
                    if (empty($this->_getCellData($cells, $this->academicYearIndex)))
                    {
                        break;
                    }

                    $this->__passStringValueIfNotEmpty($idModule,
                                                       $this->_getCellData($cells,
                                                                           $this->idModuleIndex));

                    $this->__passStringValueIfNotEmpty($moduleClassName,
                                                       $this->_getCellData($cells,
                                                                           $this->moduleClassNameIndex));

                    $this->__passStringValueIfNotEmpty($numberOfStudents,
                                                       $this->_getCellData($cells,
                                                                           $this->numberOfStudentsIndex));

                    $this->__passStringValueIfNotEmpty($numberOfStudentsReality,
                                                       $this->_getCellData($cells,
                                                                           $this->numberOfStudentsRealityIndex));

                    $this->__passStringValueIfNotEmpty($classType,
                                                       $this->_getCellData($cells,
                                                                           $this->classTypeIndex));

                    $this->__passStringValueIfNotEmpty($dateRange,
                                                       $this->_getCellData($cells,
                                                                           $this->dateIndex));

                    $this->__passStringValueIfNotEmpty($numberOfWeeks,
                                                       $this->_getCellData($cells,
                                                                           $this->numberOfWeeks));

                    $this->__createModuleClass($moduleClasses, $idModule, $moduleClassName,
                                               $classType, $numberOfStudents,
                                               $numberOfStudentsReality);

                    for ($i = 0; $i < count($this->periods); $i++)
                    {
                        if (!empty($this->_getCellData($cells, $this->periods[$i])) ||
                            !empty($this->_getCellData($cells, $this->periods[$i])))
                        {
                            $period = $this->_getCellData($cells, $this->periods[$i]);
                            $idRoom = $this->_getCellData($cells, $this->roomsIndex[$i]);
                            $this->__createSchedules($schedules, $idModule, $moduleClassName,
                                                     $dateRange, $period, $idRoom, $numberOfWeeks,
                                                     $i);
                        }
                    }
                }

                if ($this->idModuleIndex != -1 && !$isStart)
                {
                    $isStart = true;
                }
            }
        }

        return [
            'schedules'      => $schedules,
            'module_classes' => $moduleClasses,
        ];
    }

    private function __resetIndex ()
    {
        $this->idModuleIndex                = -1;
        $this->moduleClassNameIndex         = -1;
        $this->numberOfStudentsIndex        = -1;
        $this->numberOfStudentsRealityIndex = -1;
        $this->classTypeIndex               = -1;
        $this->dateIndex                    = -1;
        $this->numberOfWeeks                = -1;
        $this->periods                      = [];
        $this->roomsIndex                   = [];
        $this->academicYearIndex            = -1;
    }

    private function __passStringValueIfNotEmpty (&$string, $value)
    {
        if (empty($value))
        {
            return;
        }

        $string = $value;
    }

    private function __createModuleClass (array  &$moduleClasses, string $idModule,
                                          string $moduleClassName, string $classType,
                                          string $numberOfStudents, string $numberOfStudentsReality)
    {
        $idModuleClass = $this->_convertToIdModuleClass($idModule, $moduleClassName);

        $moduleClasses[$idModuleClass] = [
            'id'               => $idModuleClass,
            'name'             => $moduleClassName,
            'number_plan'      => $numberOfStudents,
            'number_reality'   => $numberOfStudentsReality,
            'type'             => GData::$classType[$classType],
            'id_study_session' => $this->idStudySession,
            'is_international' => strpos($idModuleClass, '(QT') === false ? 0 : 1,
            'id_module'        => $idModule,
        ];
    }

    private function __createSchedules (array  &$schedules, string $idModule,
                                        string $moduleClassName, string $dateRange, string $period,
                                        string $idRoom, string $numberOfWeeks,
                                        string $dayIndexOfWeek)
    {
        $idModuleClass       = $this->_convertToIdModuleClass($idModule, $moduleClassName);
        $firstDateOfSchedule = $this->__getFirstDateOfSchedule($dateRange, $dayIndexOfWeek);;
        $shift = $this->__getShiftByPeriod($period);

        for ($i = 0; $i < $numberOfWeeks; $i++)
        {
            $step = $i * 7;;
            $date = calculateDateTime($firstDateOfSchedule, "+{$step} days", 'Y-m-d');;
            $schedules[] = [
                'id_module_class' => $idModuleClass,
                'date'            => $date,
                'shift'           => $shift,
                'id_room'         => $idRoom,
            ];
        }
    }

    private function __getShiftByPeriod (string $period)
    {
        $period = preg_replace('/[ ]+/', '', $period);
        return GData::$shift[$period];
    }

    private function __getFirstDateOfSchedule (string $dateRange, string $dayIndexOfWeek)
    {
        $dateRange = explode('-', $dateRange);
        $date      = $dateRange[0] . '/' . explode('/', $dateRange[1])[2];
        $date      = convertDateTime($date, 'd/m/y', 'Y-m-d');
        return calculateDateTime($date, "+{$dayIndexOfWeek} days", 'Y-m-d');
    }
}