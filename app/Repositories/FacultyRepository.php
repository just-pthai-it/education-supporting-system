<?php

namespace App\Repositories;

use App\Models\Faculty;
use App\Repositories\Abstracts\BaseRepository;

class FacultyRepository extends BaseRepository implements Contracts\FacultyRepositoryContract
{
    function model () : string
    {
        return Faculty::class;
    }

    public function findAllWithDepartments ()
    {
        $this->createModel();
        return $this->model->with(['departments:id,name,id_faculty'])->get(['id', 'name']);
    }
}
