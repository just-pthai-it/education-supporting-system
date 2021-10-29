<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Notification;
use Illuminate\Support\Collection;

class NotificationRepository implements Contracts\NotificationRepositoryContract
{
    public function insertGetID ($data) : int
    {
        return Notification::create($data)->id;
    }

    public function insertPivotMultiple ($id_notification, $id_accounts)
    {
        Notification::find($id_notification)->accounts()->attach($id_accounts);
    }

    public function getNotifications1 ($id_sender, $num) : Collection
    {
        return Notification::where('id_sender', '=', $id_sender)
                           ->where('is_delete', '=', 0)
                           ->orderBy('id', 'desc')
                           ->offset($num)
                           ->limit(15)
                           ->select('id as id_notification', 'title', 'content',
                                    'time_create', 'time_start', 'time_end')->get();
    }

    public function getNotifications2 ($id_notifications) : array
    {
        $result = [];

        $result[] = Notification::whereIn('notification.id', $id_notifications)
                                ->join(Account::table_as, 'id_sender', '=', 'acc.id')
                                ->join(Department::table_as, 'id_sender', '=', 'dep.id_account')
                                ->select('notification.*',
                                         'dep.department_name as sender_name')->get()->toArray();

        $result[] = Notification::whereIn('notification.id', $id_notifications)
                                ->join(Account::table_as, 'id_sender', '=', 'acc.id')
                                ->join(Faculty::table_as, 'id_sender', '=', 'fac.id_account')
                                ->select('notification.*',
                                         'fac.faculty_name as sender_name')->get()->toArray();

        return $result;
    }

    public function getIDNotifications ($id_account, $offset)
    {
        return Account::find($id_account)->notifications()
                      ->limit(10)->offset($offset)->pluck('id_notification')->toArray();
    }

    public function update ($id_sender, $id_notifications)
    {
        Notification::whereIn('id', $id_notifications)
                    ->where('id_sender', $id_sender)->update(['is_delete' => 1]);
    }
}
