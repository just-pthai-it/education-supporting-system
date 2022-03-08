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
     * @param array $package
     */
    public function __construct (array $package)
    {
        $this->data          = $package['basic_data'];
        $this->fixedSchedule = $package['fixed_schedule'];
    }

    /**
     * Build the message.
     * @return $this
     */
    public function build () : FixedScheduleMailNotify
    {
        $this->_loadFixedScheduleRelationship();
        return $this->to($this->fixedSchedule->schedule->moduleClass->teacher->account->email)
                    ->view($this->data['view'])
                    ->with(['content'        => $this->data['content'],
                            'fixed_schedule' => $this->fixedSchedule->getOriginal(),
                            'module_class'   => $this->fixedSchedule->schedule->moduleClass->getOriginal(),
                            'teacher'        => $this->fixedSchedule->schedule->moduleClass->teacher->getOriginal()])
                    ->subject($this->data['subject']);
    }

    private function _loadFixedScheduleRelationship ()
    {
        $this->fixedSchedule->load(['schedule:id,id_module_class',
                                    'schedule.moduleClass:id,name,id_teacher',
                                    'schedule.moduleClass.teacher:id,name,is_female',
                                    'schedule.moduleClass.teacher.account:id_user,email']);
    }
}
