<?php

namespace App\Imports\Reader;

use App\Helpers\SharedFunctions;
use App\Imports\FileImport;
use Exception;

class ExcelFileReader1
{
    private string $id_training_type;

    /**
     * @throws Exception
     */
    public function readData ($file_name, $module_list, $id_training_type) : array
    {
        $this->id_training_type = $id_training_type;
        $raw_data               = $this->_getData($file_name);
        $formatted_data         = $this->_formatData($raw_data, $module_list);

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
    private function _formatData ($raw_data, $module_list) : array
    {
        $curr_mc      = '';
        $student      = [];
        $participate  = [];
        $module_class = [];
        $exception    = [];
        $temp_arr     = [];
        $temp_num     = 0;
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
                            foreach ($module_list as $module)
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
                                $module_class[] = $curr_mc;
                            }
                            foreach ($temp_arr as $id_student)
                            {
                                $participate[] = [
                                    'id_module_class' => $curr_mc,
                                    'id_student'      => $id_student
                                ];
                            }
                        }
                        else
                        {
                            $exception[] = $curr_mc;
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
                    $student[]           = $arr;
                    $temp_arr[]          = $arr['id'];
                }
            }
        }

        $flag2 = true;
        if ($temp_num == 1)
        {
            $flag2 = false;
            foreach ($module_list as $module)
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
                $module_class[] = $curr_mc;
            }
            foreach ($temp_arr as $id_student)
            {
                $participate[] = [
                    'id_module_class' => $curr_mc,
                    'id_student'      => $id_student
                ];
            }
        }
        else
        {
            $exception[] = $curr_mc;
        }

        $student      = array_unique($student, SORT_REGULAR);
        $module_class = array_unique($module_class, SORT_REGULAR);

        return [
            'student'      => $student,
            'participate'  => $participate,
            'module_class' => $module_class,
            'exception1'   => $exception
        ];
    }
}
