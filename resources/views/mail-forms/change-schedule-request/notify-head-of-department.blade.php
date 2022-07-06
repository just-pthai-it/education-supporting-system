<!DOCTYPE html>
<html lang="vi">
<head>
  <title></title>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
</head>

<body>
<table style="width: 100%; height: 100%; font-family: Arial, Helvetica, sans-serif; border-collapse: collapse; font-size: 14px;">
  <tbody>
  <tr>
    <td style="width: 100%; height: 100%; padding: 20px" class="box-wrapper">
      <table style="border-collapse: collapse; width: 600px; margin: auto; border: 1px solid #dddddd;">
        <tbody>
        <tr class="header">
          <td style="height: 100%; display: flex; padding: 1rem; border-bottom: 1px solid #dddddd;">
            <a style="display: flex; align-items: center; text-decoration: none;" href="https://utcketnoi.edu.vn"
               target="_blank">
              <img style="height: 65px; display: block; object-fit: cover;"
                   src="https://utcketnoi.edu.vn/assets/img/logo-text.png" alt=""/>
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
              Xin chào {{ $teacher['is_female'] ? 'cô' : 'thầy' }} {{ $teacher['name'] }}.<br/>
              Bộ môn {{ $department['name'] }} vừa nhận được một yêu cầu thay đổi lịch giảng.<br>
              Hãy kiểm tra ngay khi có thể.
            </p>

            <p>Trân trọng,</p>
            <div style="margin-top: 0.25rem; font-weight: bold; font-style: italic; color: #04af04;">Đội ngũ phát triển
              UTCKetnoi
            </div>
          </td>
        </tr>
        <tr class="footer">
          <td style="text-align: center; padding: 1rem; font-size: 13px;">
            <div style="height: 1px; margin: 0 5rem 1rem; background-color: #1976d2; opacity: 0.36;"></div>
            <a href="https://utcketnoi.edu.vn" target="_blank"> UTCKetnoi </a><br/>
            <span style="display: block; margin-top: 0.25rem; color: #333333;">
                      Đây là tin nhắn tự động, vui lòng không phản hồi
                    </span>
            <span style="display: block; margin-top: 0.25rem; color: #333333">
              Để đưuọc hỗ trợ, vui lòng nhắn tin qua tài khoản hỗ trợ <a href="https://m.me/utcketnoi" target="_blank">tại đây</a>
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
