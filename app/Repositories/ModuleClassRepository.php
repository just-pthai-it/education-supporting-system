<?php

namespace App\Repositories;

use App\Models\Module;
use App\Models\Teacher;
use App\Models\ModuleClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Abstracts\BaseRepository;

class ModuleClassRepository extends BaseRepository implements Contracts\ModuleClassRepositoryContract
{
    function model () : string
    {
        return ModuleClass::class;
    }

    public function findByIdDepartment ($idDepartment, array $inputs)
    {
        return $this->model->whereHas('module', function (Builder $query) use ($idDepartment)
        {
            $query->where('id_department', '=', $idDepartment);
        })->filter($inputs)->with(['module:id,name,credit',
                                   'teacher:id,name'])
                           ->get(['id', 'name', 'number_reality', 'type', 'id_module', 'id_teacher']);
    }

    public function getIDModuleClassesMissing ($id_module_classes)
    {
        $this->createModel();
        $this->_createTemporaryTable($id_module_classes);
        return $this->model->rightJoin('temp', 'id', 'id_module_class')
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


    public function softDeleteByIdDepartmentAndIdStudySession (string $idDepartment,
                                                               int    $idStudySession)
    {
        $this->createModel();
        $this->model->join(Module::TABLE_AS, 'module_classes.id_module', '=', 'mds.id')
                    ->where('id_department', '=', $idDepartment)
                    ->where('id_study_session', '=', $idStudySession)
                    ->update(['deleted_at' => now()]);
    }
}
