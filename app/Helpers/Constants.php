<?php

namespace App\Helpers;

class Constants
{
    /*
     * create notification route option
     */
    public const FOR_TEACHERS_IN_FACULTIES = 'faculties';
    public const FOR_TEACHERS_IN_DEPARTMENTS = 'departments';
    public const FOR_STUDENTS_IN_FACULTIES_AND_ACADEMIC_YEARS = 'faculties-and-academic-years';
    public const FOR_STUDENTS_IN_MODULE_CLASSES = 'module-classes';
    public const FOR_STUDENTS_BY_IDS = 'students';

    public const NOTIFICATION_TYPE = [
        self::FOR_TEACHERS_IN_FACULTIES                    => 1,
        self::FOR_TEACHERS_IN_DEPARTMENTS                  => 1,
        self::FOR_STUDENTS_IN_FACULTIES_AND_ACADEMIC_YEARS => 1,
        self::FOR_STUDENTS_IN_MODULE_CLASSES               => 1,
        self::FOR_STUDENTS_BY_IDS                          => 2,
    ];
}