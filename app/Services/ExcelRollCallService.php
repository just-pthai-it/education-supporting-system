<?php

namespace App\Services;

use App\Helpers\GData;
use App\Services\Abstracts\AExcelService;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Reader\Exception\ReaderNotOpenedException;

class ExcelRollCallService extends AExcelService
{
    private $moduleClassesWithFewStudents;
    private $idStudentsMissing;
    private $academicYears;
    private $idTrainingType;

    private int $numericalOrderIndex = 0;
    private int $classIndex = -1;
    private int $idStudentIndex = -1;
    private int $fullNameIndex = -1;
    private int $birthIndex = -1;

    private const MODULE_CLASS_ROW_INDEX = 4;

    /**
     * @throws UnsupportedTypeException
     * @throws IOException
     * @throws ReaderNotOpenedException
     */
    public function readData (string $filePath, array $parameters = []) : array
    {
        $this->moduleClassesWithFewStudents = $parameters['module_classes_with_few_students'];

        $reader = $this->_getReader($filePath);

        $students           = [];
        $tempStudents       = [];
        $moduleClassStudent = [];

        $currentIdModuleClass = '';
        $isFirstPageOfClass   = false;
        foreach ($reader->getSheetIterator() as $sheet)
        {
            $previousIdModuleClass = $currentIdModuleClass;;
            $isStart  = false;
            $rowIndex = -1;
            foreach ($sheet->getRowIterator() as $row)
            {
                $cells = $row->getCells();
                $rowIndex++;

                if ($rowIndex == self::MODULE_CLASS_ROW_INDEX)
                {
                    $currentIdModuleClass = $this->_getCellData($cells, 0);
                }

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
                        if ($cell->getValue() == 'Lớp')
                        {
                            $this->classIndex = $i;
                            continue;
                        }

                        if ($cell->getValue() == 'Mã số SV')
                        {
                            $this->idStudentIndex = $i;
                            continue;
                        }

                        if ($cell->getValue() == 'Họ và tên')
                        {
                            $this->fullNameIndex = $i;
                            continue;
                        }

                        if ($cell->getValue() == 'Ngày sinh')
                        {
                            $this->birthIndex = $i;
                            break;
                        }
                    }
                }

                if (!$isStart &&
                    is_numeric($this->_getCellData($cells, $this->numericalOrderIndex)))
                {
                    $isStart = true;

                    if ($this->_getCellData($cells, $this->numericalOrderIndex) == '1')
                    {
                        $idModuleClass = $previousIdModuleClass;
                        if ($isFirstPageOfClass)
                        {
                            $idModuleClass = $this->moduleClassesWithFewStudents[$previousIdModuleClass];
                        }

                        if (!empty($tempStudents) && !empty($idModuleClass))
                        {
                            $this->__createModuleClassStudent($moduleClassStudent,
                                                              $idModuleClass,
                                                              $tempStudents);
                        }

                        $isFirstPageOfClass = true;
                        $tempStudents       = [];
                    }
                    else
                    {
                        $isFirstPageOfClass = false;
                    }
                }

                if ($isStart)
                {
                    $this->__createStudent($students, $tempStudents, $cells);
                }
            }
        }

        if ($isFirstPageOfClass)
        {
            $idModuleClass = $this->moduleClassesWithFewStudents[$currentIdModuleClass];
        }
        else
        {
            $idModuleClass = $currentIdModuleClass;
        }

        $this->__createModuleClassStudent($moduleClassStudent, $idModuleClass, $tempStudents);

        return [
            'students'             => $students,
            'module_class_student' => $moduleClassStudent
        ];
    }

    private function __createStudent (array &$students, array &$tempStudents, array &$cells)
    {
        $idStudent = $this->_getCellData($cells, $this->idStudentIndex);
        $fullName  = $this->_getCellData($cells, $this->fullNameIndex);
        $fullName  .= " {$this->_getCellData($cells, $this->fullNameIndex+1)}";
        $idClass   = $this->_getCellData($cells, $this->classIndex);
        $birth     = $this->_getCellData($cells, $this->birthIndex);

        $student = [
            'id'       => $idStudent,
            'name'     => $fullName,
            'id_class' => $idClass,
            'birth'    => date('Y-m-d', strtotime($birth)),
        ];

        $students[]     = $student;
        $tempStudents[] = $idStudent;
    }


    private function __createModuleClassStudent (array &$moduleClassStudent, string $idModuleClass,
                                                 array $students)
    {
        $moduleClassStudent[$idModuleClass] = $students;
    }

    private function _getInfoOfFacultyClass (&$id_class)
    {
        $id_class      = str_replace('Đ', 'D', $id_class);
        $id_class      = str_replace('Ư', 'U', $id_class);
        $id_class      = preg_replace('/\s+/', '', $id_class);
        $arr           = explode('.', $id_class);
        $academic_year = array_shift($arr);

        $class = '';
        foreach ($arr as $a)
        {
            $class .= $a . '.';
        }
        $class = rtrim($class, '.');

        $num = substr($class, strlen($class) - 1);
        if (is_numeric($num))
        {
            if (!isset(GData::$faculty_class_and_major_info[substr($class, 0,
                                                                   strlen($class) - 1)]))
            {
                $class_info['id_faculty'] = 'KHOAKHAC';
                $class_info['name']       = 'Lớp thuộc khoa khác';
            }
            else
            {
                $class_info         = GData::$faculty_class_and_major_info[substr($class,
                                                                                  0,
                                                                                  strlen($class) -
                                                                                  1)];
                $name_academic_year = substr_replace($academic_year, 'hóa ', 1, 0);
                $class_info['name'] = $class_info['name'] . ' ' . $num . ' - ' .
                                      $name_academic_year;
            }
        }
        else
        {
            if (!isset(GData::$faculty_class_and_major_info[$class]))
            {
                $class_info['id_faculty'] = 'KHOAKHAC';
                $class_info['name']       = 'Lớp thuộc khoa khác';
            }
            else
            {
                $class_info         = GData::$faculty_class_and_major_info[$class];
                $name_academic_year = substr_replace($academic_year, 'hóa ', 1, 0);
                $class_info['name'] = $class_info['name'] . ' - ' . $name_academic_year;
            }
        }
        $class_info['id']               = $id_class;
        $class_info['id_academic_year'] = $this->academicYears[$academic_year];
        $class_info['id_training_type'] = strpos($id_class, 'VLVH') !==
                                          false ? 2 : $this->idTrainingType;

        return $class_info;
    }
}