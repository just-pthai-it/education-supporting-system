<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use App\Models\FixedSchedule;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FixedScheduleMailNotify extends Mailable
{
    use Queueable, SerializesModels;

    private array $data;
    private FixedSchedule $fixedSchedule;

    /**
     * @param array $data
     */
    public function __construct (array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     * @return $this
     */
    public function build () : FixedScheduleMailNotify
    {
        return $this->to($this->data['recipient'])->view($this->data['view'])
                    ->with($this->data['data'])->subject($this->data['subject']);
    }
}
