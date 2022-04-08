<?php

namespace App\Repositories\Contracts;

interface AcademicYearRepositoryContract
{
    /**
     * return an associative array with key  => value: id = > academic_years
     * @return mixed
     */
    public function getAcademicYears1 ();

    /**
     * return an associative array with key  => value: academic_years = > id
     * @return mixed
     */
    public function getAcademicYears2 ();

    /**
     * return an associative array with key  => value: academic_years = > id
     * @return mixed
     */
    public function getAcademicYears3 ();
}
