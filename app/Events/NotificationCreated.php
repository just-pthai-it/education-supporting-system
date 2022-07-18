<?php

namespace App\Events;

use App\Helpers\Constants;
use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Notification $notification;
    private array $taggableIds;
    private string $routeOption;

    /**
     * Create a new event instance.
     *
     * @param Notification $notification
     * @param array        $taggableIds
     * @param string       $routeOption
     */
    public function __construct (Notification $notification, array $taggableIds,
                                 string       $routeOption)
    {
        $this->notification = $notification;
        $this->taggableIds  = $taggableIds;
        $this->routeOption  = $routeOption;
    }

    /**
     * Get the channels the event should broadcast on.
     * @return array
     */
    public function broadcastOn () : array
    {
        $channels = [];
        switch ($this->routeOption)
        {
            case Constants::FOR_TEACHERS_IN_FACULTIES:
                $channels = collect($this->taggableIds['faculties'])->transform(function ($item)
                {
                    return "{$item}-teacher";
                })->all();
                break;

            case Constants::FOR_TEACHERS_IN_DEPARTMENTS:
                $channels = $this->taggableIds['departments'];
                break;

            case Constants::FOR_STUDENTS_IN_MODULE_CLASSES;
                $channels = $this->taggableIds['module_classes'];
                break;

            case Constants::FOR_STUDENTS_IN_FACULTIES_AND_ACADEMIC_YEARS:
                if (empty($this->taggableIds['faculties']))
                {
                    $channels = $this->taggableIds['academic_years'];
                }

                if (empty($this->taggableIds['academic_years']))
                {
                    $channels = collect($this->taggableIds['faculties'])->transform(function ($item)
                    {
                        return "{$item}-student";
                    })->all();
                }

                foreach ($this->taggableIds['academic_years'] as $department)
                {
                    foreach ($this->taggableIds['faculties'] as $faculty)
                    {
                        $channels[] = "{$faculty}.{$department}";
                    }
                }
                break;

            case Constants::FOR_STUDENTS_BY_IDS:
                $channels = $this->taggableIds['id_students'];
                break;
        }

        $channels = collect($channels)->transform(function ($item)
        {
            return str_replace(['(', ')'], '_', $item);
        });

        return $channels->all();
    }

    public function broadcastAs () : string
    {
        return 'notification-created';
    }

    /**
     * Get the data to broadcast.
     * @return array
     */
    public function broadcastWith () : array
    {
        return $this->notification->getOriginal();
    }
}
