<?php

namespace App\Repositories;

use App\Models\Module;
use App\Models\Teacher;
use App\Models\ModuleClass;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ModuleClassRepository implements Contracts\ModuleClassRepositoryContract
{
    public function insertMultiple ($data)
    {
        ModuleClass::insert($data);
    }

    public function insertPivotMultiple ($id_module_class, $id_students)
    {
        ModuleClass::find($id_module_class)->students()->attach($id_students);
    }

    public function update ($new_module_class)
    {
        $module_class = ModuleClass::find($new_module_class['id']);
        foreach ($new_module_class as $key => $value)
        {
            $module_class->$key = strpos($key, 'is_') !== false ? intval($value) : $value;
        }
        $module_class->save();
    }

    public function findByIdTeacher ($id_teacher, $id_study_sessions) : Collection
    {
        return ModuleClass::whereIn('id_study_session', $id_study_sessions)
                          ->where('id_teacher', '=', $id_teacher)
                          ->orderBy('id')
                          ->get(['id as id_module_class', 'name as module_class_name']);
    }

    public function findByIdDepartment ($id_department, $id_study_sessions)
    {
        return ModuleClass::join(Module::table_as, 'module_class.id_module', '=', 'md.id')
                     ->where('md.id_department', '=', $id_department)
                     ->leftJoin(Teacher::table_as, 'tea.id', '=', 'module_class.id_teacher')
                     ->whereIn('module_class.id_study_session', $id_study_sessions)
                     ->orderBy('module_class.id')
                     ->get(['module_class.id', 'module_class.name', 'credit', 'number_plan',
                            'class_type', 'tea.name as teacher_name']);
    }

    public function getIDModuleClassesMissing ($id_module_classes)
    {
        $this->_createTemporaryTable($id_module_classes);
        return ModuleClass::rightJoin('temp', 'id', 'id_module_class')
                          ->whereNull('id')->pluck('id_module_class')->toArray();
    }

    public function _createTemporaryTable ($id_module_classes)
    {
        $sql_query =
            'CREATE TEMPORARY TABLE temp (
                id_module_class varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

        DB::unprepared($sql_query);
        DB::table('temp')->insert($id_module_classes);
    }
}
