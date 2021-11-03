<?php

namespace App\Imports\Handler;

use App\Helpers\SharedData;

class ExcelDataHandler1
{
    public function handleData ($formatted_data, $id_training_type,
                                $id_students_missing, $academic_years) : array
    {
        $classes               = [];
        $accounts              = [];
        $available_id_students = [];
        $data_version_students = [];

        foreach ($formatted_data['students'] as $key => &$student)
        {
            if (!in_array($student['id'], $id_students_missing))
            {
                $available_id_students[] = $student['id'];
                unset($formatted_data['students'][$key]);
                continue;
            }

            $class_info = $this->_getInfoOfFacultyClass($student['id_class'],
                                                        $id_training_type, $academic_years);
            $classes[]  = $class_info;

            $accounts[] = [
                'username' => $student['id'],
                'password' => bcrypt($student['birth']),
            ];

            $data_version_students[] = [
                'id_student' => $student['id'],
                'schedule'   => '1'
            ];

        }

        return [
            'classes'               => array_unique($classes, SORT_REGULAR),
            'accounts'              => $accounts,
            'available_id_students' => $available_id_students,
            'data_version_students' => $data_version_students,
            'students'              => $formatted_data['students'],
            'participates'          => $formatted_data['participates'],
        ];
    }

    private function _getInfoOfFacultyClass (&$id_class, $id_training_type, $academic_years)
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
            if (!isset(SharedData::$faculty_class_and_major_info[substr($class, 0,
                                                                        strlen($class) - 1)]))
            {
                $class_info['id_faculty'] = 'KHOAKHAC';
                $class_info['name']       = 'Lớp thuộc khoa khác';
            }
            else
            {
                $class_info         = SharedData::$faculty_class_and_major_info[substr($class,
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
            if (!isset(SharedData::$faculty_class_and_major_info[$class]))
            {
                $class_info['id_faculty'] = 'KHOAKHAC';
                $class_info['name']       = 'Lớp thuộc khoa khác';
            }
            else
            {
                $class_info         = SharedData::$faculty_class_and_major_info[$class];
                $name_academic_year = substr_replace($academic_year, 'hóa ', 1, 0);
                $class_info['name'] = $class_info['name'] . ' - ' . $name_academic_year;
            }
        }
        $class_info['id']               = $id_class;
        $class_info['id_academic_year'] = $academic_years[$academic_year];
        $class_info['id_training_type'] = $id_training_type;

        return $class_info;
    }
}
