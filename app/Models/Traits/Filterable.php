<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeFilter (Builder $query, $parameters)
    {
        foreach ($parameters as $field => $parameterValue)
        {
            if (!is_array($parameterValue))
            {
                continue;
            }

            foreach ($parameterValue as $operator => $value)
            {
                if ($operator != 'sort')
                {
                    $this->where($query, $field, $operator, $value);
                }
                else
                {
                    $this->orderBy($query, $field, $value);
                }
            }
        }
    }

    private function where (Builder $query, $field, $operator, $value)
    {
        $operator = str_replace('equal', '=', $operator);
        $method   = 'filter' . Str::studly($field);

        if ($value == 'all')
        {
            return;
        }

        if (method_exists($this, $method))
        {
            $this->$method($query, $value);
            return;
        }

        if (empty($this->filterable) || !is_array($this->filterable))
        {
            return;
        }

        if (key_exists($field, $this->filterable))
        {
            $field = $this->filterable[$field];
        }

        if (in_array($field, $this->filterable))
        {
            switch ($operator)
            {
                case 'between':
                    $query->whereBetween("{$this->table}.{$field}", explode(',', $value));
                    break;
                case 'in':
                    $query->whereIn("{$this->table}.{$field}", explode(',', $value));
                    break;
                case 'not in':
                    $query->whereNotIn("{$this->table}.{$field}", explode(',', $value));
                    break;
                case 'like':
                    $query->where("{$this->table}.{$field}", 'like', $value);
                    break;
                case 'not like':
                    $query->where("{$this->table}.{$field}", 'not like', $value);
                    break;
                case 'null':
                    $query->whereNull("{$this->table}.{$field}");
                    break;
                case 'not null':
                    $query->whereNotNull("{$this->table}.{$field}");
                    break;
                default:
                    $query->where("{$this->table}.{$field}", $operator, $value);
            }
        }
    }

    private function orderBy (Builder $query, $field, $value)
    {
        if (empty($this->sortable) || !is_array($this->sortable))
        {
            return;
        }

        if (key_exists($field, $this->sortable))
        {
            $field = $this->sortable[$field];
        }

        if (in_array($field, $this->sortable))
        {
            $query->orderBy($field, $value);
        }
    }
}