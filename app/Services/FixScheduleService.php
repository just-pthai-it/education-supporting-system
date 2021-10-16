<?php


namespace App\Services;


use App\Imports\Handler\AmazonS3;
use App\Repositories\Contracts\FixedScheduleRepositoryContract;

class FixScheduleService implements Contracts\FixScheduleServiceContract
{
    private FixedScheduleRepositoryContract $fixDepository;
    private AmazonS3 $amazonS3s3;

    /**
     * FixScheduleService constructor.
     * @param FixedScheduleRepositoryContract $fixDepository
     * @param AmazonS3 $amazonS3s3
     */
    public function __construct (FixedScheduleRepositoryContract $fixDepository, AmazonS3 $amazonS3s3)
    {
        $this->fixDepository = $fixDepository;
        $this->amazonS3s3    = $amazonS3s3;
    }

    public function sendNotificationOfFixSchedules ()
    {
        $this->amazonS3s3->uploadFile(config('filesystems.disks.s3.file_name_1'),
                                      config('filesystems.disks.s3.file_path_1'),
                                      config('filesystems.disks.s3.cron_job_folder_1'));
    }
}
