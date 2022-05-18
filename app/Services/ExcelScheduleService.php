<?php

namespace App\Services;

use Exception;
use App\Helpers\GFunction;
use App\Services\Abstracts\AExcelService;
use App\Imports\ExamScheduleExcelFileImport;

class ExcelScheduleService extends AExcelService
{
    private int $idStudySession;

    /**
     * @throws Exception
     */
    public function readData (...$parameters) : array
    {
        $this->idStudySession = intval($parameters[0]);
        $raw_data = $this->_getData();
        return $this->_formatData($raw_data);
    }

    protected function _getData () : array
    {
        return (new ExamScheduleExcelFileImport())->toArray(request()->file);
    }

    /**
     * @throws Exception
     */
    protected function _formatData ($rawData) : array
    {
        $schedules              = [];
        $id_modules             = [];
        $module_classes         = [];
        $Special_module_classes = [];

        foreach ($rawData as $sheet)
        {
            $is_start = false;

            $current_id_module         = '';
            $current_module_class_name = '';
            $current_credit            = '';
            $max_attempt               = '';
            $real_attempt              = '';
            $current_class_type        = '';
            $current_date              = '';
            $times                     = '';

            $day_index   = [];
            $other_index = [];

            foreach ($sheet as $row)
            {
                if ($row[1] == 'STT')
                {
                    for ($i = 3; ; $i++)
                    {
                        if ($row[$i] == 'Thời gian')
                        {
                            $other_index['date']    = $i;
                            $other_index['faculty'] = $i + 17;
                        }

                        if ($row[$i] == 'Thứ 2')
                        {
                            $day_index['monday']    = $i;
                            $day_index['tuesday']   = $i += 2;
                            $day_index['wednesday'] = $i += 2;
                            $day_index['thursday']  = $i += 2;
                            $day_index['friday']    = $i += 2;
                            $day_index['saturday']  = $i + 2;
                            break;
                        }
                    }
                    continue;
                }

                if ($row[1] == 1)
                {
                    $is_start = true;
                }

                if ($is_start && !is_null($row[$other_index['faculty']]))
                {
                    $current_id_module         = $row[2] ?? $current_id_module;
                    $current_module_class_name = $row[4] ?? $current_module_class_name;
                    $current_credit            = $row[2] ?? $current_credit;
                    $max_attempt               = $row[5] ?? $max_attempt;
                    $real_attempt              = $row[6] ?? $real_attempt;
                    $current_class_type        = $row[7] ?? $current_class_type;
                    $current_date              = $row[$other_index['date']] ?? $current_date;
                    $times                     = $row[$other_index['date'] + 1] ?? $times;

                    $this->_createModuleClass($module_classes, $current_id_module,
                                              $current_module_class_name, $current_class_type,
                                              $max_attempt, $real_attempt);

                    $id_modules[] = ['id_module' => $current_id_module];

                    if ($max_attempt <= 40)
                    {
                        $Special_module_classes[$current_module_class_name] = end($module_classes)['id'];
                    }

                    $j = -1;
                    foreach ($day_index as $e)
                    {
                        $j++;
                        if (!is_null($row[$e]) &&
                            !is_null($row[$e + 1]))
                        {
                            $period  = $row[$e] ?? '';
                            $id_room = $row[$e + 1] ?? '';
                            $period  = preg_replace('/[ ]+/', '', $period);
                            $id_room = preg_replace('/[ ]+/', '', $id_room);

                            if ($period == '' || $id_room == '')
                            {
                                break;
                            }

                            $this->_createSchedule($schedules, end($module_classes)['id'],
                                                   $current_date, $period, $id_room, $times, $j);
                            break;
                        }
                    }
                }
                else if ($is_start && is_null($row[$other_index['faculty']]))
                {
                    break;
                }
            }
        }

        return [
            'schedules'              => $schedules,
            'id_modules'             => array_unique($id_modules, SORT_REGULAR),
            'module_classes'         => $module_classes,
            'special_module_classes' => array_merge($Special_module_classes,
                                                    ['id_study_session' => $this->idStudySession])
        ];
    }


    private function _createModuleClass (&$module_classes, $id_module, $module_class_name,
                                         $type, $max_attempt, $real_attempt)
    {
        $id_module_class = GFunction::convertToIDModuleClass($id_module, $module_class_name);

        $module_classes[$id_module_class] = [
            'id'               => $id_module_class,
            'name'             => $module_class_name,
            'number_plan'      => $max_attempt,
            'number_reality'   => $real_attempt,
            'type'             => $type == 'BT' ? 2 : ($type ==
                                                       'TH' ? 3 : ($type ==
                                                                   'DA' ? 4 : 1)),
            'id_study_session' => $this->idStudySession,
            'is_international' => strpos($id_module_class, '(QT') === false ? 0 : 1,
            'id_module'        => $id_module,
        ];
    }

    /**
     * @throws Exception
     */
    private function _createSchedule (&$schedules, $id_module_class, $date, $period, $id_room,
                                      $times, $day)
    {
        $exact_date = $this->_getExactDate($date, $day);
        $shift      = $this->_getShift($period);

        for ($i = 0; $i < $times; $i++)
        {
            $schedules[] = [
                'id_module_class' => $id_module_class,
                'date'            => GFunction::plusDate($exact_date, 7 * $i),
                'shift'           => $shift,
                'id_room'         => $id_room,
            ];
        }
    }

    private function _getExactDate ($date, $day)
    {
        $date = GFunction::convertToDate($date);
        return GFunction::plusDate($date, $day);
    }

    /**
     * @throws Exception
     */
    private function _getShift ($period) : string
    {
        switch ($period)
        {
            case '1,2,3':
                return '1';
            case '4,5,6':
                return '2';
            case '7,8,9':
                return '3';
            case '10,11,12':
                return '4';
            case '13,14,15':
                return '5_1';
            case '13,14,15,16':
                return '5_2';
            default:
                throw new Exception();
        }
    }

    public function setParameters (...$parameters)
    {
        $this->idStudySession = $parameters[0];
    }
}