<?php

namespace App\Repositories;

use App\Models\Class_;
use App\Repositories\Abstracts\BaseRepository;

class ClassRepository extends BaseRepository implements Contracts\ClassRepositoryContract
{
    public function model () : string
    {
        return Class_::class;
    }

    public function findIdClassesByIdAcademicYearAndIdFacultyPairs (array $idAcademicYears,
                                                                    array $idFaculties)
    {
        $this->createModel();
        foreach ($idAcademicYears as $idAcademicYear)
        {
            foreach ($idFaculties as $idFaculty)
            {
                $this->model->orWhere(function ($query) use ($idAcademicYear, $idFaculty)
                {
                    $query->where([['id_academic_year', '=', $idAcademicYear],
                                   ['id_faculty', '=', $idFaculty]]);
                });
            }
        }

        return $this->model->pluck('id');
    }
}
