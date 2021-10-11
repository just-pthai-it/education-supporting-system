<?php

namespace App\Repositories;

use App\Repositories\Contracts\ParticipateRepositoryContract;
use App\Models\Participate;
use Illuminate\Support\Facades\DB;

class ParticipateRepository implements ParticipateRepositoryContract
{
    public function getIDStudents ($class_list) : array
    {
        $this->_createTemporaryTable($class_list);

        return Participate::join('temp', 'participate.id_module_class', '=', 'temp.id_module_class')
                          ->pluck('id_student')
                          ->toArray();
    }

    public function insertMultiple ($data)
    {
        Participate::upsert($data, ['id_module_class', 'id_student'], ['id_student']);
    }

    public function _createTemporaryTable ($class_list)
    {
        $sql_query =
            'CREATE TEMPORARY TABLE temp (
                  id_module_class varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

        DB::unprepared($sql_query);
        DB::table('temp')->insert($class_list);
    }
}

