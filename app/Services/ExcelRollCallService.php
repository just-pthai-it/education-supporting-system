<?php

namespace App\Services;

use App\Helpers\GData;
use App\Helpers\GFunction;
use App\Services\Abstracts\AExcelService;
use App\Imports\ExamScheduleExcelFileImport;
use Illuminate\Support\Str;

class ExcelRollCallService extends AExcelService
{
    private $specialModuleClasses;
    private $idStudentsMissing;
    private $academicYears;
    private $idTrainingType;

    protected function _getData () : array
    {
        return (new ExamScheduleExcelFileImport())->toArray(request()->file);
    }

    protected function _formatData ($rawData) : array
    {
        $current_module_class        = '';
        $latest_first_ordinal_number = -1;

        $students               = [];
        $id_students            = [];
        $participates           = [];
        $temp_students          = [];
        $id_module_classes      = [];
        $module_classes_missing = [];
        foreach ($rawData as $sheet)
        {
            $is_begin = false;
            foreach ($sheet as $row)
            {
                if (!is_int($row[0]) && $is_begin)
                {
                    break;
                }

                if (is_int($row[0]) && !$is_begin)
                {
                    if ($row[0] == 1)
                    {
                        $this->_firstPage($latest_first_ordinal_number, $current_module_class,
                                          $temp_students, $participates, $id_module_classes,
                                          $module_classes_missing);
                    }

                    $latest_first_ordinal_number = $row[0];
                    $current_module_class        = $sheet[4][0];
                    $is_begin                    = true;
                }

                if ($is_begin)
                {
                    $arr['id']       = $row[2];
                    $arr['birth']    = GFunction::formatDate($row[5]);
                    $arr['id_class'] = $row[1];
                    $arr['name']     = $row[3] . ' ' . $row[4];

                    $students[]      = $arr;
                    $id_students[]   = ['id_student' => $arr['id']];
                    $temp_students[] = $arr['id'];
                }
            }
        }

        $this->_firstPage($latest_first_ordinal_number, $current_module_class,
                          $temp_students, $participates, $id_module_classes,
                          $module_classes_missing);

        return [
            'students'               => array_unique($students, SORT_REGULAR),
            'id_students'            => array_unique($id_students, SORT_REGULAR),
            'participates'           => $participates,
            'id_module_classes'      => $id_module_classes,
            'module_classes_missing' => $module_classes_missing,
        ];
    }

    private function _firstPage ($latest_first_ordinal_number, $current_module_class,
                                 &$temp_students, &$participates, &$id_module_classes,
                                 &$module_classes_missing)
    {
        $is_valid = true;
        if ($latest_first_ordinal_number == 1)
        {
            $is_valid = false;
            if (isset($this->specialModuleClasses[$current_module_class]))
            {
                $current_module_class = $this->specialModuleClasses[$current_module_class];
                $is_valid             = true;
            }
            else
            {
                $module_classes_missing[] = $current_module_class;
            }
        }

        if ($is_valid)
        {
            if ($current_module_class != '')
            {
                $id_module_classes[] = ['id_module_class' => $current_module_class];
            }
            foreach ($temp_students as $id_student)
            {
                $participates[$current_module_class][] = $id_student;
            }
        }

        $temp_students = [];
    }

    public function handleData ($formattedData, ...$parameters) : array
    {
        $classes               = [];
        $available_id_students = [];

        foreach ($formattedData['students'] as $key => &$student)
        {
            if (!in_array($student['id'], $this->idStudentsMissing))
            {
                $available_id_students[] = $student['id'];
                unset($formattedData['students'][$key]);
                continue;
            }

            $class_info = $this->_getInfoOfFacultyClass($student['id_class']);
            $classes[]  = $class_info;

            $student['uuid'] = GFunction::uuidToBin(Str::orderedUuid());
        }

        return [
            'classes'               => array_unique($classes, SORT_REGULAR),
            'available_id_students' => $available_id_students,
            'students'              => $formattedData['students'],
            'participates'          => $formattedData['participates'],
        ];
    }

    public function setParameters (...$parameters)
    {
        $this->specialModuleClasses = $parameters[0];
        $this->idStudentsMissing    = $parameters[1];
        $this->academicYears        = $parameters[2];
        $this->idTrainingType       = $parameters[3];
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