<?php

namespace App\Events;

use Exception;
use App\Models\Account;
use App\Models\Teacher;
use App\Helpers\Constants;
use App\Models\FixedSchedule;
use Illuminate\Broadcasting\Channel;
use App\Repositories\NotificationRepository;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Repositories\Contracts\NotificationRepositoryContract;

class FixedScheduleCreatedOrUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private FixedSchedule $fixedSchedule;
    private Account       $loggingAccount;

    /**
     * @param FixedSchedule $fixedSchedule
     * @param Account       $loggingAccount
     *
     * @throws Exception
     */
    public function __construct (FixedSchedule $fixedSchedule, Account $loggingAccount)
    {
        $this->fixedSchedule  = $fixedSchedule;
        $this->loggingAccount = $loggingAccount;
    }

    /**
     * @return FixedSchedule
     */
    public function getFixedSchedule () : FixedSchedule
    {
        return $this->fixedSchedule;
    }

    /**
     * @return Account
     */
    public function getLoggingAccount () : Account
    {
        return $this->loggingAccount;
    }
}
