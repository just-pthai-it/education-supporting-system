<?php

namespace App\Repositories;

use App\Models\OtherDepartment;
use App\Repositories\Abstracts\BaseRepository;

class OtherDepartmentRepository extends BaseRepository implements Contracts\OtherDepartmentRepositoryContract
{
    public function model () : string
    {
        return OtherDepartment::class;
    }
}