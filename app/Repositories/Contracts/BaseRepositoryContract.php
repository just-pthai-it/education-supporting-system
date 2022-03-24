<?php

namespace App\Repositories\Contracts;

interface BaseRepositoryContract
{
    public function insert (array $object);

    public function insertGetId (array $object);

    public function insertGetObject(array $object);

    public function insertMultiple (array $objects);

    public function insertPivot ($id, array $array, string $relation);

    public function syncPivot ($id, array $array, string $relation);

    public function upsert ($objects, array $uniqueColumns = [], array $columnsUpdate = []);

    public function update (array $values, array $conditions = []);

    public function updateIncrement (string $column, int $step = 1, array $conditions = []);

    public function updateByIds ($ids, $values);

    public function updateIncrementByIds ($ids, $column, int $step = 1);

    public function find (array $columns = ['*'], array $conditions = [], array $orders = [],
                          array $limitOffset = [], array $scopes = [], array $postFunctions = []);

    public function findByIds ($ids, array $columns = ['*'], array $orders = [],
                               array $scopes = [], array $postFunctions = []);

    public function pluck (array $columns = [['id']], array $conditions = [], array $orders = [],
                           array $limitOffset = [], array $scopes = []);

    public function delete (array $conditions = []);

    public function deleteByIds (array $ids);

    public function count (array $conditions = []);

    public function paginate (array $columns = ['*'], array $conditions = [], array $orders = [],
                              int   $pagination = 1, array $scopes = []);
}