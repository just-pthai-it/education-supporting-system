<?php

namespace App\Repositories\Contracts;

interface BaseRepositoryContract
{
    public function insert (array $object);

    public function insertGetId (array $object);

    public function insertGetObject (array $object);

    public function insertMultiple (array $objects);

    public function insertPivot ($id, array $array, string $relation);

    public function syncPivot ($id, array $array, string $relation);

    public function upsert (array $values, array $uniqueColumns = [], array $columnsUpdate = []);

    public function updateGetUpdatedRows (array $values, array $conditions = [],
                                          array $scopes = []);

    public function update (array $values, array $conditions = [], array $scopes = []);

    public function updateByIds ($ids, array $values);

    public function updateExistingPivot (string $lcId, array $fkIds, string $relationship,
                                         array  $values);

    public function updateIncrement (string $column, array $conditions = [], int $step = 1,
                                     array  $scopes = []);

    public function updateIncrementByIds ($ids, string $column, int $step = 1);

    public function find (array $columns = ['*'], array $conditions = [], array $orders = [],
                          array $limitOffset = [], array $scopes = [], array $postFunctions = []);

    public function findByIds ($ids, array $columns = ['*'], array $orders = [],
                               array $limitOffset = [], array $postFunctions = []);

    public function pluck (array $columns = ['id'], array $conditions = [], array $orders = [],
                           array $limitOffset = [], array $scopes = []);

    public function pluckByIds ($ids, array $columns = ['name'], array $orders = [],
                                array $limitOffset = []);

    public function delete (array $conditions = [], array $scopes = []);

    public function deleteByIds ($ids);

    public function deletePivot ($id, array $array, string $relation);

    public function softDelete (array $conditions = [], array $scopes = []);

    public function softDeleteByIds ($ids);

    public function count (array $conditions = []);

    public function checkIfPivotExist (string $lcId, array $fkIds, string $relationship,
                                       string $fkColumn);

    public function paginate (array $columns = ['*'], array $conditions = [], array $orders = [],
                              int   $pagination = 1, array $scopes = []);
}