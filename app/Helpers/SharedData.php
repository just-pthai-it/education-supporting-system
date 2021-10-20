<?php

namespace App\Helpers;

class SharedData
{
    public static array $id_faculties_not_query = [
        'LLCT', 'GDTC', 'GDQP'
    ];

    public static array $faculty_class_and_major_info = [
        'CKOTO'        => ['class_name' => 'Lớp Cơ khí ô tô',
                           'id_faculty' => 'CK'],
        'CDT'          => ['class_name' => 'Lớp Cơ điện tử',
                           'id_faculty' => 'CK'],
        'CNCTCK'       => ['class_name' => 'Lớp Công nghệ chế tạo cơ khí',
                           'id_faculty' => 'CK'],
        'MXD'          => ['class_name' => 'Lớp Máy xây dựng',
                           'id_faculty' => 'CK'],
        'TDHTKCK'      => ['class_name' => 'Lớp Tự động hoá thiết kế cơ khí',
                           'id_faculty' => 'CK'],
        'VLVH.CNTT'    => ['class_name' => 'Lớp Công nghệ thông tin (Hệ vừa làm vừa học)',
                           'id_faculty' => 'CNTT'],
        'CNTT'         => ['class_name' => 'Lớp Công nghệ thông tin',
                           'id_faculty' => 'CNTT'],
        'CTGTCC'       => ['class_name' => 'Lớp Công trình giao thông công chính',
                           'id_faculty' => 'CT'],
        'CTGTDT'       => ['class_name' => 'Lớp Công trình giao thông đô thị',
                           'id_faculty' => 'CT'],
        'CDBO'         => ['class_name' => 'Lớp Kỹ thuật cầu đường bộ',
                           'id_faculty' => 'CT'],
        'CDS'          => ['class_name' => 'Lớp Kỹ thuật cầu đường sắt',
                           'id_faculty' => 'CT'],
        'KTGIS'        => ['class_name' => 'Lớp Kỹ thuật GIS và trắc địa công trình giao thông',
                           'id_faculty' => 'CT'],
        'CH'           => ['class_name' => 'Lớp Kỹ thuật xây dựng cầu',
                           'id_faculty' => 'CT'],
        'KTXDCTGT(QT)' => ['class_name' => 'Lớp Kỹ thuật xây dựng công trình giao thông (QT)',
                           'id_faculty' => 'CT'],
        'DHMETRO'      => ['class_name' => 'Lớp Kỹ thuật xây dựng đường hầm và metro',
                           'id_faculty' => 'CT'],
        'DSDT'         => ['class_name' => 'Lớp Kỹ thuật đường sắt đô thị',
                           'id_faculty' => 'CT'],
        'TDH'          => ['class_name' => 'Lớp Tự động hoá thiết kế cầu đường',
                           'id_faculty' => 'CT'],
        'DBO'          => ['class_name' => 'Lớp Đường bộ',
                           'id_faculty' => 'CT'],
        'KTDTVT'       => ['class_name' => 'Lớp Kỹ thuật điện tử viễn thông',
                           'id_faculty' => 'DDT'],
        'KTDTTHCN'     => ['class_name' => 'Lớp Kỹ thuật điện tử và tin học công nghiệp',
                           'id_faculty' => 'DDT'],
        'KTĐK&TDH'     => ['class_name' => 'Lớp Kỹ thuật điều khiển và tự động hoá',
                           'id_faculty' => 'DDT'],
        'TBD'          => ['class_name' => 'Lớp Trang bị điện trong công nghiệp và giao thông',
                           'id_faculty' => 'DDT'],
        'KCXD'         => ['class_name' => 'Lớp Kết cấu xây dựng',
                           'id_faculty' => 'KTXD'],
        'KTHTDT'       => ['class_name' => 'Lớp Kỹ thuật hạ tầng đô thị',
                           'id_faculty' => 'KTXD'],
        'VLCNXDGT'     => ['class_name' => 'Lớp Vật liệu và công nghệ xây dựng',
                           'id_faculty' => 'KTXD'],
        'XDDDCN'       => ['class_name' => 'Lớp Xây dựng dân dụng và công nghiệp',
                           'id_faculty' => 'KTXD'],
        'KTVTOTO'      => ['class_name' => 'Lớp Kinh tế vận tải ô tô',
                           'id_faculty' => 'VTKT'],
        'KTVTDL'       => ['class_name' => 'Lớp Kinh tế vận tải và du lịch',
                           'id_faculty' => 'VTKT'],
        'QTDNBCVT'     => ['class_name' => 'Lớp Quản trị doanh nghiệp bưu chính viễn thông',
                           'id_faculty' => 'VTKT'],
        'QTDNVT'       => ['class_name' => 'Lớp Quản trị doanh nghiệp vận tải',
                           'id_faculty' => 'VTKT']
    ];
}
