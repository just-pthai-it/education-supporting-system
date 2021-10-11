<?php

namespace App\Repositories;

use App\Repositories\Contracts\NotificationAccountRepositoryContract;
use App\Models\Account;
use App\Models\NotificationAccount;
use App\Models\Student;

class NotificationAccountRepository implements NotificationAccountRepositoryContract
{
    public function insertMultiple ($data)
    {
        NotificationAccount::insert($data);
    }

    public function getIDAccounts ($id_notification_list)
    {
        return NotificationAccount::whereIn('notification_account.id_notification', $id_notification_list)
                                  ->join(Student::table_as, 'notification_account.id_account', '=', 'stu.id_account')
                                  ->pluck('stu.id');
    }

    public function getIDNotifications ($id_account, $offset)
    {
        return Account::find($id_account)->notifications()
                      ->limit(10)->offset($offset)->pluck('id_notification')->toArray();
    }
}
