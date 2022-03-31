<?php

namespace App\Events;

use App\Models\FixedSchedule;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FixedScheduleUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private FixedSchedule $fixedSchedule;

    /**
     * @return FixedSchedule
     */
    public function getFixedSchedule () : FixedSchedule
    {
        return $this->fixedSchedule;
    }

    /**
     * @param FixedSchedule $fixedSchedule
     */
    public function __construct (FixedSchedule $fixedSchedule)
    {
        $this->fixedSchedule = $fixedSchedule;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
