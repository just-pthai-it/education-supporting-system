<?php

namespace App\Repositories;

use App\Models\ModuleClass;
use App\Models\SchoolYear;
use App\Repositories\Contracts\ModuleClassRepositoryContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ModuleClassRepository implements ModuleClassRepositoryContract
{
    public function insert ($data)
    {
        ModuleClass::insert($data);
    }

    public function insertPivotMultiple ($id_module_class, $id_students)
    {
        ModuleClass::find($id_module_class)->students()->attach($id_students);
    }

    public function getModuleClasses ($id_teacher) : Collection
    {
        return ModuleClass::where('id_school_year', '>=', SchoolYear::max('id') - 6)
                          ->where('id_teacher', '=', $id_teacher)
                          ->orderBy('id')
                          ->select('id as id_module_class', 'module_class_name')
                          ->get();
    }

    public function getIDModuleClassesNotInDatabase ($id_module_classes)
    {
        $this->_createTemporaryTable($id_module_classes);
        return ModuleClass::rightJoin('temp', 'id', '=', 'id_module_class')
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
