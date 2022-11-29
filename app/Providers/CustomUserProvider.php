<?php

namespace App\Providers;

use App\Helpers\GFunction;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

class CustomUserProvider extends EloquentUserProvider implements UserProvider
{
    public function retrieveById ($identifier)
    {
        $model = $this->createModel();

        $columns = [
            'id',
            'password',
            'email',
            'phone',
            'id_role',
            'accountable_type',
            'accountable_id',
            'settings',
            GFunction::uuidFromBin('uuid'),
        ];

        return $this->newModelQuery($model)
                    ->where($model->getAuthIdentifierName(), $identifier)
                    ->first($columns);
    }

    public function retrieveByCredentials (array $credentials)
    {
        if (empty($credentials) ||
            (count($credentials) === 1 &&
             Str::contains($this->firstCredentialKey($credentials), 'password')))
        {
            return;
        }

        // First we will add each credential element to the query as a where clause.
        // Then we can execute the query and, if we found a user, return it in a
        // Eloquent User "model" that will be utilized by the Guard instances.
        $query = $this->newModelQuery();

        foreach ($credentials as $key => $value)
        {
            if (Str::contains($key, 'password'))
            {
                continue;
            }

            if (is_array($value) || $value instanceof Arrayable)
            {
                $query->whereIn($key, $value);
            }
            else
            {
                $query->where($key, $value);
            }
        }

        $columns = [
            'id',
            'password',
            'id_role',
            'email',
            'phone',
            'accountable_type',
            'accountable_id',
            'settings',
            GFunction::uuidFromBin('uuid'),
        ];

        return $query->first($columns);
    }
}


