<!DOCTYPE html>
<html lang="en">
<head>
    <title></title>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link
            href="https://fonts.googleapis.com/css2?family=Manrope:wght@700&display=swap&text=HỆTỐNGQUẢLÝỊCẢDẠY"
            rel="stylesheet"
    />
    <style>
        * {
            padding: 0;
            margin: 0;
            border: none;
            box-sizing: border-box;
        }

        html {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
        }

        :root {
            --gray: #dddddd;
            --blue: #1976d2;
            --green: #04af04;
            --red: #ff0000;
        }

        table {
            border-collapse: collapse;
        }

        .d-flex {
            display: flex;
        }

        .align-items-center {
            align-items: center;
        }

        .w-full {
            width: 100%;
        }

        .h-full {
            height: 100%;
        }

        .text-align-center {
            text-align: center;
        }

        .pending {
            color: var(--blue);
        }

        .accept {
            color: var(--green);
        }

        .deny {
            color: var(--red);
        }

        .box-wrapper {
            padding: 20px;
        }

        .box-wrapper > table {
            width: 600px;
            margin: auto;
            border: 1px solid var(--gray);
        }

        .content-wrapper > tr > td {
            padding: 1rem;
        }

        .header > td {
            border-bottom: 1px solid var(--gray);
        }

        .header a {
            text-decoration: none;
        }

        .header a > span {
            margin-left: 0.75rem;
            color: #000000;
            font-size: 20px;
            font-family: "Manrope";
        }

        .header img {
            height: 65px;
            display: block;
            object-fit: cover;
        }

        .body > td {
            padding-top: 1.5rem !important;
            padding-bottom: 1.5rem !important;
        }

        .body .change-info-wrapper {
            margin: 1rem 0.5rem;
            padding: 1rem;
            background-color: #f6f7f8;
        }

        .body .change-info td {
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
        }

        .body .change-info td:nth-child(2) {
            padding-right: 1rem;
            padding-left: 0.125rem;
        }

        .body .change-info td:nth-child(4) {
            padding: 0 1rem 0 0;
        }

        .body .change-info td svg {
            width: 24px;
            height: 24px;
        }

        .body .change-info td svg path {
            fill: var(--blue);
        }

        .body .no-room {
            font-style: italic;
        }

        .body .status {
            font-weight: bold;
        }

        .body .dev {
            margin-top: 0.25rem;
            font-weight: bold;
            font-style: italic;
            color: var(--green);
        }

        .footer > td {
            padding: 1rem;
            font-size: 13px;
        }

        .footer .line {
            height: 1px;
            margin: 0 5rem 1rem;
            background-color: var(--blue);
            opacity: 0.36;
        }

        .footer .automation {
            display: block;
            margin-top: 0.25rem;
            color: #333333;
        }
    </style>
</head>

<body>
<table class="w-full h-full">
    <tbody>
    <tr>
        <td class="box-wrapper w-full h-full">
            <table>
                <tbody class="content-wrapper">
                <tr class="header">
                    <td class="d-flex h-full">
                        <a
                                href="https://utcketnoi.edu.vn"
                                class="h-full d-flex align-items-center"
                        >
                            <img
                                    src="https://utcketnoi.edu.vn/assets/img/logo.jpg"
                                    class="h-full"
                            />
                            <span> HỆ THỐNG QUẢN LÝ LỊCH GIẢNG DẠY </span>
                        </a>
                    </td>
                </tr>
                <tr class="body">
                    <td>
                        <p>
                            Xin chào {{ $teacher['is_female'] ? 'cô' : 'thầy' }} {{ $teacher['name'] }},<br/>
                            {{ $content }}
                            {{ $teacher['is_female'] ? 'cô' : 'thầy' }},
                            {{ in_array($fixed_schedule['status'], [2, 3]) ? 'lịch giảng dạy mới đã được cập nhật trên hệ thống,' : '' }}
                            thông tin chi tiết:
                        </p>
                        <div class="change-info-wrapper">
                            <table class="w-full">
                                <tbody class="change-info">
                                <tr>
                                    <td><b>Lớp học phần</b></td>
                                    <td>:</td>
                                    <td colspan="3">{{ $module_class['name'] }}</td>
                                </tr>
                                <tr>
                                    <td><b>Ngày học</b></td>
                                    <td>:</td>
                                    <td>{{ $fixed_schedule['old_date'] }}</td>
                                    <td>
                                        <svg viewBox="0 0 24 24">
                                            <path
                                                    d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z"
                                            />
                                        </svg>
                                    </td>
                                    <td>{{ $fixed_schedule['new_date'] }}</td>
                                </tr>
                                <tr>
                                    <td><b>Ca học</b></td>
                                    <td>:</td>
                                    <td>{{ $fixed_schedule['old_shift'] }}</td>
                                    <td>
                                        <svg viewBox="0 0 24 24">
                                            <path
                                                    d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z"
                                            />
                                        </svg>
                                    </td>
                                    <td>{{ $fixed_schedule['new_shift'] }}</td>
                                </tr>
                                <tr>
                                    <td><b>Phòng học</b></td>
                                    <td>:</td>
                                    <td>{{ $fixed_schedule['old_id_room'] }}</td>
                                    <td>
                                        <svg viewBox="0 0 24 24">
                                            <path
                                                    d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z"
                                            />
                                        </svg>
                                    </td>
                                    <td {{ isset($fixed_schedule['new_id_room']) ? '' : 'class=no-room' }}>
                                        {{ $fixed_schedule['new_id_room'] ?? '(Chưa xếp phòng)' }}
                                    </td>
                                </tr>
                                {{--                                <tr>--}}
                                {{--                                    <td><b>Phòng học</b></td>--}}
                                {{--                                    <td>:</td>--}}
                                {{--                                    <td>101-A3</td>--}}
                                {{--                                    <td>--}}
                                {{--                                        <svg viewBox="0 0 24 24">--}}
                                {{--                                            <path--}}
                                {{--                                                    d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z"--}}
                                {{--                                            />--}}
                                {{--                                        </svg>--}}
                                {{--                                    </td>--}}
                                {{--                                    <td class="no-room">(Chưa xếp phòng)</td>--}}
                                {{--                                </tr>--}}
                                <tr>
                                    <td><b>Trạng thái hiện tại</b></td>
                                    <td>:</td>
                                    <td class="status" colspan="3">
                                      <span class="{{ in_array($fixed_schedule['status'], [-3, -2, -1]) ? 'deny' : (in_array($fixed_schedule['status'], [0, 1]) ? 'pending' : 'accept') }}">
                                        {{
                                          $fixed_schedule['status'] == -3
                                          ? 'Đã hủy'
                                          : ($fixed_schedule['status'] == -2
                                          ? 'Phòng QLGĐ đã từ chối'
                                          : ($fixed_schedule['status'] == -1
                                          ? 'Bộ môn đã từ chối'
                                          : ($fixed_schedule['status'] == 0
                                          ? 'Đang chờ bộ môn phê duyệt'
                                          : ($fixed_schedule['status'] == 1
                                          ? 'Đang chờ phòng QLGĐ xếp phòng'
                                          : 'Đã phê duyệt'))))
                                          }}
                                      </span>
                                    </td>
                                </tr>
                                @if (isset($fixed_schedule['reason_deny']))
                                    <tr>
                                        <td><b>Lý do từ chối</b></td>
                                        <td>:</td>
                                        <td colspan="3">{{ $fixed_schedule['reason_deny'] }}</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <p>Trân trọng,</p>
                        <div class="dev">Đội ngũ phát triển UTCKetnoi</div>
                    </td>
                </tr>
                <tr class="footer">
                    <td class="text-align-center">
                        <div class="line"></div>
                        <a href="https://utcketnoi.edu.vn"> UTCKetnoi </a><br/>
                        <span class="automation">
                      Đây là tin nhắn tự động, vui lòng không phản hồi
                    </span>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
