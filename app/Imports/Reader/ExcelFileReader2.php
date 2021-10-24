<?php

namespace App\Imports\Reader;

use App\Helpers\SharedFunctions;
use App\Imports\FileImport;
use Exception;

class ExcelFileReader2
{
    /**
     * @throws Exception
     */
    public function readData ($file_name, $id_school_year) : array
    {
        $raw_data       = $this->_getData($file_name);
        $formatted_data = $this->_formatData($raw_data, $id_school_year);
        return $formatted_data;
    }

    private function _getData ($file_name) : array
    {
        return (new FileImport())->toArray(storage_path('app/public/excels/') . $file_name);
    }

    /**
     * @throws Exception
     */
    private function _formatData ($raw_data, $id_school_year) : array
    {

        $module_classes         = [];
        $schedules              = [];
        $Special_module_classes = [];

        foreach ($raw_data as $sheet)
        {
            $current_id_module         = '';
            $current_module_class_name = '';
            $current_credit            = '';
            $current_student_num       = '';
            $current_date              = '';
            $times                     = '';

            $day_index   = [];
            $other_index = [];

            $sheet_length = count($sheet);
            for ($i = 0; $i < $sheet_length; $i++)
            {

                if (isset($other_index['date']) &&
                    $sheet[$i][$other_index['faculty']] != null)
                {
                    $current_id_module         = $sheet[$i][2] ?? $current_id_module;
                    $temp_module_class_name    = str_replace('(', ' (', $sheet[$i][4]);
                    $current_module_class_name = $temp_module_class_name != '' ? $temp_module_class_name :
                                                    $current_module_class_name;
                    $current_credit            = $sheet[$i][2] ?? $current_credit;
                    $current_student_num       = $sheet[$i][5] ?? $current_student_num;
                    $current_date              = $sheet[$i][$other_index['date']] ?? $current_date;
                    $times                     = $sheet[$i][$other_index['date'] + 1] ?? $times;

                    $this->_createModuleClass($module_classes, $current_id_module,
                                              $current_module_class_name,
                                              $current_student_num,
                                              $id_school_year);

                    if ($current_student_num <= 40)
                    {
                        $Special_module_classes[$current_module_class_name] = end($module_classes)['id'];
                    }

                    $k = -1;
                    foreach ($day_index as $e)
                    {
                        $k++;
                        if ($sheet[$i][$e] != null
                            && $sheet[$i][$e + 1] != null
                            && $e > 10)
                        {
                            $period  = $sheet[$i][$e] ?? '';
                            $id_room = $sheet[$i][$e + 1] ?? '';
                            $period  = preg_replace('/[ ]+/', '', $period);
                            $id_room = preg_replace('/[ ]+/', '', $id_room);

                            if ($period == '' || $id_room == '')
                            {
                                break;
                            }

                            $this->_createSchedule($schedules, end($module_classes)['id'],
                                                   $current_date, $period, $id_room, $times, $k);
                            break;
                        }
                    }
                }

                if ($sheet[$i][1] == 'STT')
                {
                    for ($j = 3; ; $j++)
                    {
                        if ($sheet[$i][$j] == 'Thời gian')
                        {
                            $other_index['date']    = $j;
                            $other_index['faculty'] = $j + 17;
                        }
                        if ($sheet[$i][$j] == 'Thứ 2')
                        {
                            $day_index['monday']    = $j;
                            $day_index['tuesday']   = $j += 2;
                            $day_index['wednesday'] = $j += 2;
                            $day_index['thursday']  = $j += 2;
                            $day_index['friday']    = $j += 2;
                            $day_index['saturday']  = $j + 2;
                            break;
                        }
                    }
                    $i++;
                }
            }
        }

        return [
            'module_classes'         => $module_classes,
            'schedules'              => $schedules,
            'special_module_classes' => array_merge($Special_module_classes, ['id_school_year' => $id_school_year])
        ];
    }

    private function _createModuleClass (&$module_classes, $id_module, $module_class_name, $student_num,
                                         $id_school_year)
    {
        $id_module_class = SharedFunctions::convertToIDModuleClass($id_module, $module_class_name);

        $module_classes[$id_module_class] = [
            'id'                => $id_module_class,
            'module_class_name' => $module_class_name,
            'number_plan'       => $student_num,
            'id_school_year'    => $id_school_year,
            'id_module'         => $id_module,
        ];
    }

    /**
     * @throws Exception
     */
    private function _createSchedule (&$schedules, $id_module_class, $date, $period, $id_room, $times, $day)
    {
        $exact_date = $this->_getExactDate($date, $day);
        $shift      = $this->_getShift($period);

        for ($i = 0; $i < $times; $i++)
        {
            $schedules[] = [
                'id_module_class' => $id_module_class,
                'date'            => SharedFunctions::plusDate($exact_date, 7 * $i),
                'shift'           => $shift,
                'id_room'         => $id_room,
            ];
        }
    }

    private function _getExactDate ($date, $day)
    {
        $date = SharedFunctions::convertToDate($date);
        return SharedFunctions::plusDate($date, $day);
    }

    /**
     * @throws Exception
     */
    private function _getShift ($period) : int
    {
        switch ($period)
        {
            case '1,2,3':
                return 1;
            case '4,5,6':
                return 2;
            case '7,8,9':
                return 3;
            case '10,11,12':
                return 4;
            default:
                throw new Exception();
        }
    }
}
