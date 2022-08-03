<?php

namespace App\Helpers;

class Constants
{
    /*
     * create notification route option
     */
    public const FOR_TEACHERS_IN_FACULTIES                    = 'faculties';
    public const FOR_TEACHERS_IN_DEPARTMENTS                  = 'departments';
    public const FOR_STUDENTS_IN_FACULTIES_AND_ACADEMIC_YEARS = 'faculties-and-academic-years';
    public const FOR_STUDENTS_IN_MODULE_CLASSES               = 'module-classes';
    public const FOR_STUDENTS_BY_IDS                          = 'students';

    public const NOTIFICATION_TYPE = [
        self::FOR_TEACHERS_IN_FACULTIES                    => 1,
        self::FOR_TEACHERS_IN_DEPARTMENTS                  => 1,
        self::FOR_STUDENTS_IN_FACULTIES_AND_ACADEMIC_YEARS => 1,
        self::FOR_STUDENTS_IN_MODULE_CLASSES               => 1,
        self::FOR_STUDENTS_BY_IDS                          => 2,
    ];

    /**
     * Contains all fixed schedule status code
     * @var array
     */
    public const FIXED_SCHEDULE_STATUS = [
        'cancel'  => [
            'normal' => 100,
        ],
        'pending' => [
            'normal'   => 200,
            'soft'     => 201,
            'set_room' => 202,
        ],
        'approve' => [
            'normal'   => 300,
            'soft'     => 301,
            'straight' => 302,
        ],
        'change'  => [
            'normal' => 400,
        ],
        'deny'    => [
            'accept'   => 500,
            'set_room' => 501
        ],
    ];

    public const FIXED_SCHEDULE_UPDATED_NOTIFICATION = [

    ];

    public const FIXED_SCHEDULE_CREATED_NOTIFICATION = [
        'view'    => 'mail-forms.change-schedule-request.notify-head-of-department',
        'subject' => 'Tiếp nhận yêu cầu thay đổi lịch giảng.',
        'content' => 'Xin chào :gender :name.<br>' . "<br>" .
                     'Bộ môn :department_name vừa nhận được một yêu cầu thay đổi lịch giảng.<br>' .
                     'Hãy kiểm tra ngay khi có thể.'
    ];
}