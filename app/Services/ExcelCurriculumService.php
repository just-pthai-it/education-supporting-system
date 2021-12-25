<?php

namespace App\Services;

use App\Helpers\GFunction;
use App\Imports\FileImport;

class ExcelCurriculumService implements Contracts\ExcelServiceContract
{

    public function readData ($file_name, ...$params) : array
    {
        $raw_data = $this->_getData($file_name);
        return $this->_formatData($raw_data);
    }

    private function _getData ($file_name) : array
    {
        return (new FileImport())->toArray(storage_path('app/public/excels/') . $file_name);
    }

    private function _formatData ($raw_data) : array
    {
        $curriculum = [];
        $modules    = [];
        $id_modules = [];
        $semester   = 1;
        $option     = 1;
        foreach ($raw_data as &$sheet)
        {
            $this->_createCurriculum($curriculum, $sheet[1][0]);
            $is_begin = false;
            foreach ($sheet as $index => &$row)
            {
                if (is_int($row[0]) && !$is_begin)
                {
                    $is_begin = true;
                }

                if ($is_begin)
                {
                    if (!is_int($row[0]) &&
                        strpos($row[1], 'HỌC KỲ') !== false)
                    {
                        $semester++;
                    }

                    if (is_int($row[0]))
                    {
                        if (is_null($row[2]))
                        {
                            $credit = $row[3];
                            for ($i = ++$index; !is_int($sheet[$i][0]) &&
                                                !is_null($sheet[$i][2]); $i++)
                            {
                                $row_        = $sheet[$i];
                                $module_name = substr($row_[1], 2);
                                $credit      = $credit ?? $row_[3];
                                $this->_createModule($modules, $id_modules, $row_[2], $module_name,
                                                     $semester, $credit, $row_[4], $row_[5],
                                                     $row_[7], $row_[8], $row_[9], $option,
                                                     $row_[12]);
                            }
                            $option++;
                        }
                        else
                        {
                            $this->_createModule($modules, $id_modules, $row[2], $row[1],
                                                 $semester, $row[3], $row[4], $row[5],
                                                 $row[7], $row[8], $row[9], 0, $row[12]);
                        }
                    }
                }
            }
        }

        return [
            'curriculum' => $curriculum,
            'modules'    => $modules,
            'id_modules' => $id_modules,
        ];
    }

    private function _createModule (&$modules, &$id_modules, $id_module, $module_name,
                                    $semester, $credit, $theory, $exercise,
                                    $project, $experiment, $practice, $option, $id_department)
    {
        $id_department = (is_null($id_department) ||
                          strpos($id_department, '=') !== false) ? substr($id_module, 0,
                                                                          3) : $id_department;

        $modules[] = [
            'id'            => $id_module,
            'name'          => GFunction::formatString($module_name),
            'credit'        => $credit,
            'semester'      => $semester,
            'theory'        => $theory,
            'exercise'      => $exercise,
            'project'       => $project,
            'experiment'    => $experiment,
            'practice'      => $practice,
            'option'        => $option,
            'id_department' => $id_department,
        ];

        $id_modules[] = $id_module;
    }

    private function _createCurriculum (&$curriculums, $curriculum_name)
    {
        $curriculums = ['name' => $curriculum_name,];
    }

    public function handleData ($formatted_data, ...$params)
    {
        // TODO: Implement handleData() method.
    }

    public function setParameters (...$parameters)
    {
        // TODO: Implement setParameters() method.
    }
}