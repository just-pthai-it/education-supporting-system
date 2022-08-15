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
        self::FOR_TEACHERS_IN_FACULTIES                    => 2,
        self::FOR_TEACHERS_IN_DEPARTMENTS                  => 2,
        self::FOR_STUDENTS_IN_FACULTIES_AND_ACADEMIC_YEARS => 2,
        self::FOR_STUDENTS_IN_MODULE_CLASSES               => 2,
        self::FOR_STUDENTS_BY_IDS                          => 1,
        'accounts'                                         => 1,
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

    public const FIXED_SCHEDULE_MAIL_NOTIFICATION_FOR_TEACHER = [
        self::FIXED_SCHEDULE_STATUS['cancel']['normal']  => [
            'view'    => 'mail-forms.change-schedule-request.update-status',
            'subject' => 'Xác nhận hủy yêu cầu thay đổi lịch giảng dạy',
            'content' => 'Xin chào :teacher_gender :teacher_name.<br>' .
                         'Hủy thành công yêu cầu thay đổi lịch giảng dạy của :teacher_gender.',
        ],
        self::FIXED_SCHEDULE_STATUS['pending']['normal'] => [
            'view'    => 'mail-forms.change-schedule-request.update-status',
            'subject' => 'Xác nhận yêu cầu thay đổi lịch giảng dạy',
            'content' => 'Xin chào :teacher_gender :teacher_name.<br>' .
                         'Hệ thống đã tiếp nhận yêu cầu thay đổi lịch giảng dạy của :teacher_gender.',
        ],
        self::FIXED_SCHEDULE_STATUS['pending']['soft']   => [
            'view'    => 'mail-forms.change-schedule-request.update-status',
            'subject' => 'Xác nhận yêu cầu thay đổi lịch giảng dạy',
            'content' => 'Xin chào :teacher_gender :teacher_name.<br>' .
                         'Hệ thống đã tiếp nhận yêu cầu thay đổi lịch giảng dạy của :teacher_gender.',
        ],

        self::FIXED_SCHEDULE_STATUS['pending']['set_room'] => [
            'view'    => 'mail-forms.change-schedule-request.update-status',
            'subject' => 'Bộ môn đã phê duyệt yêu cầu thay đổi lịch giảng dạy',
            'content' => 'Xin chào :teacher_gender :teacher_name.<br>' .
                         'Bộ môn đã phê duyệt yêu cầu thay đổi lịch giảng dạy của :teacher_gender.',
        ],
        self::FIXED_SCHEDULE_STATUS['approve']['normal']   => [
            'view'    => 'mail-forms.change-schedule-request.update-status',
            'subject' => 'Phòng quản lí giảng đường đã phê duyệt yêu cầu thay đôi lịch giảng dạy',
            'content' => 'Xin chào :teacher_gender :teacher_name.<br>' .
                         'Phòng quản lí giảng đường đã phê duyệt và cấp phòng cho yêu cầu thay đổi lịch giảng dạy của :teacher_gender.',
        ],
        self::FIXED_SCHEDULE_STATUS['approve']['soft']     => [
            'view'    => 'mail-forms.change-schedule-request.update-status',
            'subject' => 'Yêu cầu thay đổi lịch giảng dạy đã được phê duyệt',
            'content' => 'Xin chào :teacher_gender :teacher_name.<br>' .
                         'Bộ môn đã phê duyệt yêu cầu thay đổi lịch giảng dạy của :teacher_gender.',
        ],
        self::FIXED_SCHEDULE_STATUS['approve']['straight'] => [
            'view'    => 'mail-forms.change-schedule-request.update-status',
            'subject' => 'Yêu cầu thay đổi lịch giảng dạy đã được phê duyệt',
            'content' => 'Xin chào :teacher_gender :teacher_name.<br>' .
                         'Bộ môn đã phê duyệt yêu cầu thay đổi lịch giảng dạy của :teacher_gender.',
        ],
        self::FIXED_SCHEDULE_STATUS['deny']['accept']      => [
            'view'    => 'mail-forms.change-schedule-request.update-status',
            'subject' => 'Bộ môn đã từ chối yêu cầu thay đổi lịch giảng dạy',
            'content' => 'Xin chào :teacher_gender :teacher_name.<br>' .
                         'Bộ môn đã từ chối yêu cầu thay đổi lịch giảng dạy của :teacher_gender.',
        ],
        self::FIXED_SCHEDULE_STATUS['deny']['set_room']    => [
            'view'    => 'mail-forms.change-schedule-request.update-status',
            'subject' => 'Phòng quản lí giảng đường đã từ chối yêu cầu thay đổi lịch giảng dạy',
            'content' => 'Xin chào :teacher_gender :teacher_name.<br>' .
                         'Phòng quản lí giảng đường đã từ chối yêu cầu thay đổi lịch giảng dạy của :teacher_gender.',
        ],
    ];

    public const FIXED_SCHEDULE_MAIL_NOTIFICATION_FOR_HEAD_OF_DEPARTMENT = [
        self::FIXED_SCHEDULE_STATUS['pending']['normal'] => [
            'view'    => 'mail-forms.change-schedule-request.notify-head-of-department',
            'subject' => 'Tiếp nhận yêu cầu thay đổi lịch giảng',
            'content' => 'Xin chào :teacher_gender :teacher_name.<br>' .
                         'Bộ môn :department_name vừa nhận được một yêu cầu thay đổi lịch giảng dạy.<br>' .
                         'Hãy kiểm tra ngay khi có thể.',
        ],
        self::FIXED_SCHEDULE_STATUS['pending']['soft']   => [
            'view'    => 'mail-forms.change-schedule-request.notify-head-of-department',
            'subject' => 'Tiếp nhận yêu cầu thay đổi lịch giảng',
            'content' => 'Xin chào :teacher_gender :teacher_name.<br>' .
                         'Bộ môn :department_name vừa nhận được một yêu cầu thay đổi lịch giảng dạy.<br>' .
                         'Hãy kiểm tra ngay khi có thể.',
        ],
    ];

    public const FIXED_SCHEDULE_BROADCAST_NOTIFICATION_FOR_TEACHER = [
        self::FIXED_SCHEDULE_STATUS['pending']['set_room'] => [
            'content' => 'Yêu cầu thay đổi lịch giảng dạy cho lớp :module_class_name đã được bộ môn phê duyệt.',
        ],
        self::FIXED_SCHEDULE_STATUS['approve']['normal']   => [
            'content' => 'Yêu cầu thay đổi lịch giảng dạy cho lớp :module_class_name đã được phòng quản lý giảng dường phê duyệt.',
        ],
        self::FIXED_SCHEDULE_STATUS['approve']['soft']     => [
            'content' => 'Yêu cầu thay đổi lịch giảng dạy cho lớp :module_class_name đã được bộ môn phê duyệt.',
        ],
        self::FIXED_SCHEDULE_STATUS['approve']['straight'] => [
            'content' => 'Yêu cầu thay đổi lịch giảng dạy cho lớp :module_class_name đã được bộ môn phê duyệt.',
        ],
        self::FIXED_SCHEDULE_STATUS['deny']['accept']      => [
            'content' => 'Yêu cầu thay đổi lịch giảng dạy cho lớp :module_class_name đã bị bộ môn từ chối.',
        ],
        self::FIXED_SCHEDULE_STATUS['deny']['set_room']    => [
            'content' => 'Yêu cầu thay đổi lịch giảng dạy cho lớp :module_class_name đã bị phòng quản lý giảng dường từ chối.',
        ],
    ];

    public const FIXED_SCHEDULE_BROADCAST_NOTIFICATION_FOR_HEAD_OF_DEPARTMENT = [
        self::FIXED_SCHEDULE_STATUS['pending']['normal'] => [
            'content' => ':teacher_name vừa tạo một yêu cầu thay đổi lịch giảng dạy cho lớp :module_class_name.',
        ],
        self::FIXED_SCHEDULE_STATUS['pending']['soft']   => [
            'content' => ':teacher_name vừa tạo một yêu cầu thay đổi lịch giảng dạy cho lớp :module_class_name.',
        ],
    ];

    public const FIXED_SCHEDULE_BROADCAST_NOTIFICATION_FOR_ROOM_MANAGER = [
        self::FIXED_SCHEDULE_STATUS['pending']['set_room'] => [
            'content' => ':teacher_name vừa tạo một yêu cầu thay đổi lịch giảng dạy cho lớp :module_class_name.',
        ],
    ];
}