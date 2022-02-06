<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title></title>
    <style>
        div {
            margin-left: 20%;
            width: 60%;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #000000;
            text-align: center;
        }

        table.table-parent th {
            padding: 8px;
        }

        table.table-parent td {
            padding: 0;
        }

        table.table-child td,
        table.table-child th {
            border: 1px solid #b8b4b4;
            padding: 8px;
        }
    </style>
</head>

<body>
<div>
    <p> {{ $content }}</p>
    @php
        $reason_deny = $reason_deny ?? null;
    @endphp

    @if (!is_null($reason_deny))
        <p> {{ $reason_deny }}</p>
    @endif
    <table class="table-parent">
        <tr>
            <th>Lịch cũ</th>
            <th>Lịch mới</th>
        </tr>
        <tr>
            <td>
                <table class="table-child">
                    <tr>
                        <th>Ngày</th>
                        <th>Ca</th>
                        <th>Phòng</th>
                    </tr>
                    <tr>
                        <td>{{ $old_date }}</td>
                        <td>{{ $old_shift }}</td>
                        <td>{{ $old_id_room }}</td>
                    </tr>
                </table>
            </td>
            <td>
                <table class="table-child">
                    <tr>
                        <th>Ngày</th>
                        <th>Ca</th>
                        <th>Phòng</th>
                    </tr>
                    <tr>
                        <td>{{ $new_date }}</td>
                        <td>{{ $new_shift }}</td>
                        <td>{{ $new_id_room ?? '' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</body>

</html>