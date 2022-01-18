<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Facades\DB;

interface BaseRepositoryContract
{
    public function insert (array $object);

    public function insertGetId (array $object);

    public function insertMultiple (array $objects);

    public function upsert ($objects, array $uniqueColumns = [],
                            array $columnsUpdate = ['id' => DB::raw('id')]);

    public function update (array $values, array $conditions = []);

    public function updateIncrement (string $column, int $step = 1, array $conditions = []);

    public function updateByIds ($ids, $values);

    public function updateIncrementByIds ($ids, $column, int $step = 1);

    public function find (array $columns = ['*'], int $limit = null, int $offset = null,
                          array $conditions1 = [], array $conditions2 = []);

    public function findByIds ($ids, array $columns = ['*']);

    public function delete (array $conditions = []);

    public function deleteByIds (array $ids);

    public function count (array $conditions = []);

}