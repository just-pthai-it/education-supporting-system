<?php

namespace App\Repositories\Abstracts;

use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\BaseRepositoryContract;
use Illuminate\Contracts\Container\BindingResolutionException;

abstract class BaseRepository implements BaseRepositoryContract
{
    protected $model;

    public function __construct ()
    {
        $this->createModel();
    }

    /**
     * Specify Model class name
     * @return string
     */
    abstract function model () : string;

    /**
     * @return mixed
     */
    public function createModel ()
    {
        try
        {
            $model = app()->make($this->model());
            return $this->model = $model;
        }
        catch (BindingResolutionException $e)
        {
            return null;
        }
    }

    public function filter (array $parameters)
    {
        $this->model->filter($parameters);
    }

    public function insert (array $object)
    {
        $this->createModel();
        return $this->model->create($object);
    }

    public function insertGetId (array $object)
    {
        $this->createModel();
        return $this->model->create($object)->id;
    }

    public function insertGetObject (array $object)
    {
        $this->createModel();
        return $this->model->create($object);
    }

    public function insertMultiple (array $objects)
    {
        $this->createModel();
        $this->model->insert($objects);
    }

    public function insertPivot ($id, array $array, string $relation)
    {
        $this->createModel();
        $this->model->find($id)->$relation()->attach($array);
    }

    public function syncPivot ($id, array $array, string $relation)
    {
        $this->createModel();
        $this->model->find($id)->$relation()->sync($array);
    }

    public function upsert (array $values, array $uniqueColumns = [], array $columnsUpdate = [])
    {
        if (empty($columnsUpdate))
        {
            $columnsUpdate['id'] = DB::raw('id');
        }

        $this->createModel();
        $this->model->upsert($values, $uniqueColumns, $columnsUpdate);
    }

    public function updateGetUpdatedRows (array $values, array $conditions = [], array $scopes = [])
    {
        $this->createModel();
        $this->addWhere($conditions);
        $this->addScopes($scopes);
        return $this->model->update($values);
    }

    public function update (array $values, array $conditions = [], array $scopes = [])
    {
        $this->createModel();
        $this->addWhere($conditions);
        $this->addScopes($scopes);
        $this->model->update($values);
    }

    public function updateIncrement (string $column, array $conditions = [], int $step = 1,
                                     array  $scopes = [])
    {
        $this->createModel();
        $this->addWhere($conditions);
        $this->addScopes($scopes);
        $this->model->increment($column, $step);
    }

    public function updateByIds ($ids, array $values)
    {
        $this->createModel();
        $this->model->whereIn('id', is_array($ids) ? $ids : [$ids])->update($values);
    }

    public function updateIncrementByIds ($ids, $column, int $step = 1)
    {
        $this->createModel();
        $this->model->whereIn('id', $ids)->increment($column, $step);
    }

    public function find (array $columns = ['*'], array $conditions = [], array $orders = [],
                          array $limitOffset = [], array $scopes = [], array $postFunctions = [])
    {
        $this->createModel();
        $this->addWhere($conditions);
        $this->addScopes($scopes);
        $this->addOrderBy($orders);
        $this->addLimitOffset($limitOffset);
        $result = $this->model->get($columns);
        $this->addPostFunction($result, $postFunctions);
        return $result;
    }

    public function findByIds ($ids, array $columns = ['*'], array $orders = [],
                               array $limitOffset = [], array $postFunctions = [])
    {
        $this->createModel();
        $this->addOrderBy($orders);
        $this->addLimitOffset($limitOffset);
        $result = $this->model->find($ids, $columns);
        $this->addPostFunction($result, $postFunctions);
        return $result;
    }

    public function pluck (array $columns = ['id'], array $conditions = [], array $orders = [],
                           array $limitOffset = [], array $scopes = [])
    {
        $this->createModel();
        $this->addWhere($conditions);
        $this->addScopes($scopes);
        $this->addWhere($conditions);
        $this->addLimitOffset($limitOffset);
        return $this->model->pluck($columns[0], $columns[1] ?? null);
    }

    public function pluckByIds ($ids, array $columns = ['name'], array $orders = [],
                                array $limitOffset = [])
    {
        $this->createModel();
        $this->addLimitOffset($limitOffset);
        return $this->model->whereIn('id', is_array($ids) ? $ids : [$ids])
                           ->pluck($columns[0], $columns[1] ?? null);
    }

    public function delete (array $conditions = [], array $scopes = [])
    {
        $this->createModel();
        $this->addWhere($conditions);
        $this->addScopes($scopes);
        return $this->model->delete();
    }

    public function deleteByIds ($ids)
    {
        $this->createModel();
        $this->model->whereIn('id', is_array($ids) ? $ids : [$ids])->delete();
    }

    public function softDelete (array $conditions = [], array $scopes = [])
    {
        $this->createModel();
        $this->addWhere($conditions);
        $this->addScopes($scopes);
        return $this->model->update(['deleted_at' => now()]);
    }

    public function softDeleteByIds ($ids)
    {
        $this->createModel();
        $this->model->whereIn('id', is_array($ids) ? $ids : [$ids])
                    ->update(['deleted_at' => now()]);
    }

    public function count (array $conditions = [])
    {
        $this->createModel();
        $this->addWhere($conditions);
        return $this->model->count();
    }

    //    public function checkExist ($id)
    //    {
    //        //reset model
    //        $this->createModel();
    //
    //        return $this->model->find($id) ? true : false;
    //    }


    public function paginate (array $columns = ['*'], array $conditions = [], array $orders = [],
                              int   $pagination = 1, array $scopes = [])
    {
        $this->createModel();
        $this->addScopes($scopes);
        $this->addWhere($conditions);
        $this->addOrderBy($orders);
        return $this->model->paginate($pagination, $columns);
    }

    //    public function checkExistBy (array $condition)
    //    {
    //        //reset model
    //        $this->createModel();
    //        $this->addWhere($condition);
    //        $data = $this->model->get();
    //        if ($data->isEmpty())
    //        {
    //            return false;
    //        }
    //        else
    //        {
    //            return true;
    //        }
    //    }

    protected function addScopes (array $scopes)
    {
        if (empty($scopes))
        {
            return;
        }

        foreach ($scopes as $arr)
        {
            $scope       = array_shift($arr);
            $this->model = $this->model->$scope(...$arr);
        }
    }

    protected function addOrderBy (array $orders = [])
    {
        if (empty($orders))
        {
            return;
        }

        foreach ($orders as $order)
        {
            $this->model = $this->model->orderBy(...$order);
        }
    }

    protected function addWhere (array $conditions = [])
    {
        if (empty($conditions))
        {
            return;
        }

        foreach ($conditions as $condition)
        {
            $attribute = $condition[0];
            $operator  = $condition[1];
            $value     = $condition[2] ?? null;

            $method = 'where';
            if (strpos($operator, '|') !== false)
            {
                $operator = str_replace('|', '', $operator);
                $method   = 'orWhere';
            }

            switch ($operator)
            {
                case 'between':
                    $this->model = $this->model->{"{$method}Between"}($attribute);
                    break;
                case 'in':
                    $this->model = $this->model->{"{$method}In"}($attribute, $value);
                    break;
                case 'not in':
                    $this->model = $this->model->{"{$method}NotIn"}($attribute, $value);
                    break;
                case 'null':
                    $this->model = $this->model->{"{$method}Null"}($attribute);
                    break;
                case 'not null':
                    $this->model = $this->model->{"{$method}NotNull"}($attribute);
                    break;
                default:
                    $this->model = $this->model->{$method}($attribute, $operator, $value);
            }
        }
    }

    protected function addLimitOffset (array $pagination)
    {
        if (empty($pagination))
        {
            return;
        }

        if (count($pagination) == 1)
        {
            $this->model = $this->model->take($pagination[0]);
        }
        else
        {
            $this->model = $this->model->limit($pagination[0])->offset($pagination[1]);
        }
    }

    protected function addPostFunction (&$result, array $functions)
    {
        if (empty($functions))
        {
            return;
        }

        foreach ($functions as $arr)
        {
            $function = array_shift($arr);
            $result   = $result->$function(...$arr);
        }
    }
}
