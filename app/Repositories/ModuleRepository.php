<?php

namespace App\Repositories;

use App\Models\Module;
use Illuminate\Support\Facades\DB;
use App\Repositories\Abstracts\BaseRepository;

class ModuleRepository extends BaseRepository implements Contracts\ModuleRepositoryContract
{
    public function model () : string
    {
        return Module::class;
    }

    public function getIDModulesMissing ($id_modules)
    {
        $this->createModel();
        $this->_createTemporaryTable($id_modules);
        return $this->model->rightJoin('temp_module', 'id', 'id_module')
                           ->whereNull('id')->pluck('id_module')->toArray();
    }

    public function _createTemporaryTable ($id_modules)
    {
        $sql_query =
            'CREATE TEMPORARY TABLE temp_module (
                id_module varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

        DB::unprepared($sql_query);
        DB::table('temp_module')->insert($id_modules);
    }
}
