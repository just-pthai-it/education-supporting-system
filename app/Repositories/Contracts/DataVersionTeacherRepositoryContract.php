<?php


namespace App\Repositories\Contracts;


interface DataVersionTeacherRepositoryContract
{
    public function get ($id_teacher);

    public function getSingleColumn1 ($id_account, $column_name);

    public function getSingleColumn2 ($id_teacher, $column_name);
}
