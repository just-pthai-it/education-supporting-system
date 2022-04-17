<!DOCTYPE html>
<html lang="vi">
<head>
  <title></title>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <style>
      .pending {
          color: #1976d2;
      }

      .accept {
          color: #04af04;
      }

      .deny {
          color: #ff0000;
      }
  </style>
</head>

<body>
<table style="width: 100%; height: 100%; font-family: Arial, Helvetica, sans-serif; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">
  <tbody>
  <tr>
    <td style="width: 100%; height: 100%; padding: 20px" class="box-wrapper">
      <table style="border-collapse: collapse; width: 600px; margin: auto; border: 1px solid #dddddd;">
        <tbody>
        <tr class="header">
          <td style="height: 100%; display: flex; padding: 1rem; border-bottom: 1px solid #dddddd;">
            <a style="display: flex; align-items: center; text-decoration: none;" href="https://utcketnoi.edu.vn">
              <img style="height: 100%; height: 65px; display: block; object-fit: cover;"
                   src="https://utcketnoi.edu.vn/assets/img/logo.jpg"/>
              <span style="margin-left: 0.75rem; color: #000000; font-size: 20px; font-weight: 600;">
                        HỆ THỐNG QUẢN LÝ LỊCH GIẢNG DẠY
                      </span>
            </a>
          </td>
        </tr>
        <tr class="body">
          <td
                  style="padding: 1rem; padding-top: 1.5rem !important; padding-bottom: 1.5rem !important;">
            <p>
              Xin chào {{ $teacher['is_female'] ? 'cô' : 'thầy' }} {{ $teacher['name'] }},<br/>
              {{ $content }}
              {{ $teacher['is_female'] ? 'cô' : 'thầy' }}
              {{ in_array($fixed_schedule['status'],$fs_status_code['approve'])
                                                    ? ', lịch giảng dạy mới đã được cập nhật trên hệ thống.' : '.' }}
              Thông tin chi tiết:
            </p>
            <div style="margin: 1rem 0.5rem; padding: 1rem; background-color: #f6f7f8;">
              <table style="width: 100%; border-collapse: collapse">
                <tbody class="change-info">
                <tr>
                  <td style="padding-top: 0.25rem; padding-bottom: 0.25rem;">
                    <b>Lớp học phần</b>
                  </td>
                  <td style="padding: 0.25rem 1rem 0.25rem 0.125rem;">
                    :
                  </td>
                  <td colspan="3" style="padding-top: 0.25rem; padding-bottom: 0.25rem;">
                    {{ $module_class['name'] }}
                  </td>
                </tr>
                <tr>
                  <td style="padding-top: 0.25rem; padding-bottom: 0.25rem;">
                    <b>Ngày học</b>
                  </td>
                  <td style="padding: 0.25rem 1rem 0.25rem 0.125rem;">
                    :
                  </td>
                  <td style="padding-top: 0.25rem; padding-bottom: 0.25rem;">
                    {{ $fixed_schedule['old_date'] }}
                  </td>
                  <td style="padding: 0 1rem 0 0;">
                    <img src="https://utcketnoi.edu.vn/assets/img/chevron-right.png"/>
                  </td>
                  <td style="padding-top: 0.25rem; padding-bottom: 0.25rem; {{ isset($fixed_schedule['new_date']) ? '' : 'font-style: italic;' }}">
                    {{ $fixed_schedule['new_date'] ?? '(Chưa xác định)'}}
                  </td>
                </tr>
                <tr>
                  <td style="padding-top: 0.25rem; padding-bottom: 0.25rem;">
                    <b>Ca học</b>
                  </td>
                  <td style="padding: 0.25rem 1rem 0.25rem 0.125rem;">
                    :
                  </td>
                  <td style="padding-top: 0.25rem; padding-bottom: 0.25rem;">
                    {{ $fixed_schedule['old_shift'] }}
                  </td>
                  <td style="padding: 0 1rem 0 0;">
                    <img src="https://utcketnoi.edu.vn/assets/img/chevron-right.png"/>
                  </td>
                  <td style="padding-top: 0.25rem; padding-bottom: 0.25rem; {{ isset($fixed_schedule['new_shift']) ? '' : 'font-style: italic;' }}">
                    {{ $fixed_schedule['new_shift'] ?? '(Chưa xác định)'}}
                  </td>
                </tr>
                <tr>
                  <td style="padding-top: 0.25rem; padding-bottom: 0.25rem;">
                    <b>Phòng học</b>
                  </td>
                  <td style="padding: 0.25rem 1rem 0.25rem 0.125rem;">
                    :
                  </td>
                  <td style="padding-top: 0.25rem; padding-bottom: 0.25rem;">
                    {{ $fixed_schedule['old_id_room'] }}
                  </td>
                  <td style="padding: 0 1rem 0 0;">
                    <img src="https://utcketnoi.edu.vn/assets/img/chevron-right.png"/>
                  </td>
                  <td style="padding-top: 0.25rem; padding-bottom: 0.25rem; {{ isset($fixed_schedule['new_id_room']) ? '' : 'font-style: italic;' }}">
                    {{ $fixed_schedule['new_id_room'] ?? '(Chưa xếp phòng)' }}
                  </td>
                </tr>
                @if (!is_null($fixed_schedule['intend_time']))
                  <tr>
                    <td style="padding-top: 0.25rem; padding-bottom: 0.25rem;">
                      <b>Thời gian dự kiến</b>
                    </td>
                    <td style="padding: 0.25rem 1rem 0.25rem 0.125rem;">
                      :
                    </td>
                    <td style="padding-top: 0.25rem; padding-bottom: 0.25rem;">
                      {{ $fixed_schedule['intend_time'] }}
                    </td>
                  </tr>
                @endif
                <tr>
                  <td style="padding-top: 0.25rem; padding-bottom: 0.25rem;">
                    <b>Trạng thái hiện tại</b>
                  </td>
                  <td style="padding: 0.25rem 1rem 0.25rem 0.125rem;">
                    :
                  </td>
                  <td style="font-weight: bold; {{
                                                in_array($fixed_schedule['status'], array_merge(array_values($fs_status_code['cancel']),
                                                                                                array_values($fs_status_code['deny'])))
                                                ? 'color: #ff0000' : (in_array($fixed_schedule['status'], $fs_status_code['pending']) 
                                                ? 'color: #1976d2' : 'color: #04af04')
                                                }}"
                      colspan="3">
                              <span class="pending">
                               @if($fixed_schedule['status'] == $fs_status_code['cancel']['normal'])
                                  Đã hủy
                                @elseif($fixed_schedule['status'] == $fs_status_code['deny']['set_room'])
                                  Phòng QLGĐ đã từ chối
                                @elseif($fixed_schedule['status'] == $fs_status_code['deny']['accept'])
                                  Bộ môn đã từ chối
                                @elseif(in_array($fixed_schedule['status'], [$fs_status_code['pending']['normal'],
                                                                                 $fs_status_code['pending']['soft']]))
                                  Đang chờ bộ môn phê duyệt
                                @elseif($fixed_schedule['status'] == $fs_status_code['pending']['set_room'])
                                  Đang chờ phòng QLGĐ xếp phòng
                                @elseif(in_array($fixed_schedule['status'], $fs_status_code['approve']))
                                  Đã phê duyệt
                                @endif
                              </span>
                  </td>
                </tr>
                @if (isset($fixed_schedule['reason_deny']))
                  <tr>
                    <td style="padding-top: 0.25rem; padding-bottom: 0.25rem;">
                      <b>Lý do từ chối</b>
                    </td>
                    <td style="padding: 0.25rem 1rem 0.25rem 0.125rem;">
                      :
                    </td>
                    <td style="padding-top: 0.25rem; padding-bottom: 0.25rem;"
                        colspan="3">{{ $fixed_schedule['reason_deny'] }}</td>
                  </tr>
                @endif
                </tbody>
              </table>
            </div>
            <p>Trân trọng,</p>
            <div style="margin-top: 0.25rem; font-weight: bold; font-style: italic; color: #04af04;">Đội ngũ phát triển
              UTCKetnoi
            </div>
          </td>
        </tr>
        <tr class="footer">
          <td style="text-align: center; padding: 1rem; font-size: 13px;">
            <div style="height: 1px; margin: 0 5rem 1rem; background-color: #1976d2; opacity: 0.36;"></div>
            <a href="https://utcketnoi.edu.vn"> UTCKetnoi </a><br/>
            <span style="display: block; margin-top: 0.25rem; color: #333333;">
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
