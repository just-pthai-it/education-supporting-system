<?php

namespace App\Imports\Reader;

use App\Helpers\SharedFunctions;
use App\Imports\FileImport;
use Exception;

class ExcelFileReader1
{
    /**
     * @throws Exception
     */
    public function readData ($file_name, $special_module_classes) : array
    {
        $raw_data       = $this->_getData($file_name);
        $formatted_data = $this->_formatData($raw_data, $special_module_classes);

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
    private function _formatData ($raw_data, $special_module_classes) : array
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
                            if (isset($special_module_classes[$curr_mc]))
                            {
                                $curr_mc = $special_module_classes[$curr_mc];
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
                    $arr['birth']    = SharedFunctions::formatDate($row[5]);
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
            if (isset($special_module_classes[$curr_mc]))
            {
                $curr_mc = $special_module_classes[$curr_mc];
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
}
