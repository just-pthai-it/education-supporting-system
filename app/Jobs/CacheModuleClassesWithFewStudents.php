<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CacheModuleClassesWithFewStudents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $moduleClassesWithFewStudents;
    private string $idStudySession;
    private string $idDepartment;

    /**
     * Create a new job instance.
     *
     * @param array  $moduleClassesWithFewStudents
     * @param string $idStudySession
     * @param string $idDepartment
     */
    public function __construct (array  $moduleClassesWithFewStudents, string $idStudySession,
                                 string $idDepartment)
    {
        $this->moduleClassesWithFewStudents = $moduleClassesWithFewStudents;
        $this->idStudySession               = $idStudySession;
        $this->idDepartment                 = $idDepartment;
    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle ()
    {
        $key = "{$this->idStudySession}_{$this->idDepartment}_module_classes_with_few_students";
        Cache::put($key, $this->moduleClassesWithFewStudents, now()->addDays(15));
    }
}
