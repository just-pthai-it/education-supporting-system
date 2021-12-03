<?php

namespace App\Services;

use Exception;
use App\Helpers\GData;
use App\Helpers\GFunction;
use App\Imports\FileImport;
use Illuminate\Support\Str;

class RollCallExcelService implements Contracts\ExcelServiceContract
{
    private array $special_module_classes;
    private array $id_students_missing;
    private array $academic_years;
    private string $id_training_type;

    /**
     * @param array $special_module_classes
     */
    public function setSpecialModuleClasses (array $special_module_classes) : void
    {
        $this->special_module_classes = $special_module_classes;
    }

    /**
     * @param array $id_students_missing
     */
    public function setIdStudentsMissing (array $id_students_missing) : void
    {
        $this->id_students_missing = $id_students_missing;
    }

    /**
     * @param array $academic_years
     */
    public function setAcademicYears (array $academic_years) : void
    {
        $this->academic_years = $academic_years;
    }

    /**
     * @param string $id_training_type
     */
    public function setIdTrainingType (string $id_training_type) : void
    {
        $this->id_training_type = $id_training_type;
    }


    public function readData ($file_name)
    {
        $raw_data       = $this->_getData($file_name);
        $formatted_data = $this->_formatData($raw_data);
        return $formatted_data;
    }

    private function _getData ($file_name) : array
    {
        $raw_data = (new FileImport())->toArray(storage_path('app/public/excels/') . $file_name);
        return $raw_data;
    }

    /**
     * @throws Exception
     */
    private function _formatData ($raw_data) : array
    {
        $curr_mc    = '';
        $latest_num = 0;

        $students               = [];
        $id_students            = [];
        $participates           = [];
        $temp_students          = [];
        $id_module_classes      = [];
        $module_classes_missing = [];
        foreach ($raw_data as $sheet)
        {
            $flag = false;
            foreach ($sheet as $row)
            {
                if (!is_int($row[0]) && $flag)
                {
                    break;
                }

                if (is_int($row[0]) && !$flag)
                {
                    if ($row[0] == 1)
                    {
                        $flag2 = true;
                        if ($row[0] == $latest_num && $latest_num == 1)
                        {
                            $flag2 = false;
                            if (isset($this->special_module_classes[$curr_mc]))
                            {
                                $curr_mc = $this->special_module_classes[$curr_mc];
                                $flag2   = true;
                            }
                            else
                            {
                                $module_classes_missing[] = $curr_mc;
                            }
                        }

                        if ($flag2)
                        {
                            if ($curr_mc != '')
                            {
                                $id_module_classes[] = ['id_module_class' => $curr_mc];
                            }
                            foreach ($temp_students as $id_student)
                            {
                                $participates[$curr_mc][] = $id_student;
                            }
                        }

                        $temp_students = [];
                    }

                    $latest_num = $row[0];
                    $curr_mc    = $sheet[4][0];
                    $flag       = true;
                }
                if ($flag)
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

        $flag2 = true;
        if ($latest_num == 1)
        {
            $flag2 = false;
            if (isset($this->special_module_classes[$curr_mc]))
            {
                $curr_mc = $this->special_module_classes[$curr_mc];
                $flag2   = true;
            }
            else
            {
                $module_classes_missing[] = $curr_mc;
            }
        }

        if ($flag2)
        {
            if ($curr_mc != '')
            {
                $id_module_classes[] = ['id_module_class' => $curr_mc];
            }
            foreach ($temp_students as $id_student)
            {
                $participates[$curr_mc][] = $id_student;
            }
        }

        return [
            'students'               => array_unique($students, SORT_REGULAR),
            'id_students'            => array_unique($id_students, SORT_REGULAR),
            'participates'           => $participates,
            'id_module_classes'      => $id_module_classes,
            'module_classes_missing' => $module_classes_missing,
        ];
    }

    public function handleData ($formatted_data) : array
    {
        $classes               = [];
        $available_id_students = [];

        foreach ($formatted_data['students'] as $key => &$student)
        {
            if (!in_array($student['id'], $this->id_students_missing))
            {
                $available_id_students[] = $student['id'];
                unset($formatted_data['students'][$key]);
                continue;
            }

            $class_info = $this->_getInfoOfFacultyClass($student['id_class']);
            $classes[]  = $class_info;

            $student['uuid'] = GFunction::uuidToBin(Str::orderedUuid());
        }

        return [
            'classes'               => array_unique($classes, SORT_REGULAR),
            'available_id_students' => $available_id_students,
            'students'              => $formatted_data['students'],
            'participates'          => $formatted_data['participates'],
        ];
    }

    private function _getInfoOfFacultyClass (&$id_class)
    {
        $id_class      = str_replace('Đ', 'D', $id_class);
        $id_class      = preg_replace('/\s+/', '', $id_class);
        $arr           = explode('.', $id_class);
        $academic_year = $arr[0];

        unset($arr[0]);
        $class = '';
        foreach ($arr as $a)
        {
            $class .= $a . '.';
        }
        $class = rtrim($class, '.');

        $num = substr($class, strlen($class) - 1, 1);
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
        $class_info['id_academic_year'] = $this->academic_years[$academic_year];
        $class_info['id_training_type'] = $this->id_training_type;

        return $class_info;
    }
}