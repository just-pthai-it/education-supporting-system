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
    public function readData ($file_name, $modules) : array
    {
        $raw_data       = $this->_getData($file_name);
        $formatted_data = $this->_formatData($raw_data, $modules);

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
    private function _formatData ($raw_data, $modules) : array
    {
        $curr_mc          = '';
        $students         = [];
        $id_students      = [];
        $participates     = [];
        $module_classes   = [];
        $module_exception = [];
        $temp_arr         = [];
        $temp_num         = 0;
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
                        if ($row[0] == $temp_num && $temp_num == 1)
                        {
                            $flag2 = false;
                            foreach ($modules as $module)
                            {
                                if (strpos($curr_mc, $module['module_name']) !== false)
                                {
                                    $curr_mc = str_replace($module['module_name'], $module['id_module'], $curr_mc);
                                    $flag2   = true;
                                    break;
                                }
                            }
                        }

                        if ($flag2)
                        {
                            if ($curr_mc != '')
                            {
                                $module_classes[] = ['id_module_class' => $curr_mc];
                            }
                            foreach ($temp_arr as $id_student)
                            {
                                $participates[$curr_mc][] = $id_student;
                            }
                        }
                        else
                        {
                            $module_exception[] = $curr_mc;
                        }

                        $temp_arr = [];
                    }

                    $temp_num = $row[0];
                    $curr_mc  = $sheet[4][0];
                    $flag     = true;
                }
                if ($flag)
                {
                    $arr['id']           = $row[2];
                    $arr['student_name'] = $row[3] . ' ' . $row[4];
                    $arr['birth']        = SharedFunctions::formatDate($row[5]);
                    $arr['id_class']     = $row[1];

                    $students[]    = $arr;
                    $id_students[] = ['id_student' => $arr['id']];
                    $temp_arr[]    = $arr['id'];
                }
            }
        }

        $flag2 = true;
        if ($temp_num == 1)
        {
            $flag2 = false;
            foreach ($modules as $module)
            {
                if (strpos($curr_mc, $module['module_name']) !== false)
                {
                    $curr_mc = str_replace($module['module_name'], $module['id_module'], $curr_mc);
                    $flag2   = true;
                    break;
                }
            }
        }

        if ($flag2)
        {
            if ($curr_mc != '')
            {
                $module_classes[] = ['id_module_class' => $curr_mc];
            }
            foreach ($temp_arr as $id_student)
            {
                $participates[$curr_mc][] = $id_student;
            }
        }
        else
        {
            $module_exception[] = $curr_mc;
        }

        $students       = array_unique($students, SORT_REGULAR);
        $module_classes = array_unique($module_classes, SORT_REGULAR);
        $id_students    = array_unique($id_students, SORT_REGULAR);

        return [
            'students'         => $students,
            'id_students'      => $id_students,
            'participates'     => $participates,
            'module_classes'   => $module_classes,
            'module_exception' => $module_exception
        ];
    }
}
