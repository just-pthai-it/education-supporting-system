<?php

namespace App\Repositories\Contracts;

interface AcademicYearRepositoryContract
{

    /**
     * return an associative array with key  => value: id = > academic_year
     * @return mixed
     */
    public function getAcademicYears1 ();

    /**
     * return an associative array with key  => value: academic_year = > id
     * @return mixed
     */
    public function getAcademicYears2 ();
}