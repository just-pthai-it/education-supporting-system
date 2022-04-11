<?php

namespace App\Services;

use App\Repositories\Contracts\RoomRepositoryContract;

class RoomService implements Contracts\RoomServiceContract
{
    private RoomRepositoryContract $roomDepository;

    /**
     * @param RoomRepositoryContract $roomDepository
     */
    public function __construct (RoomRepositoryContract $roomDepository)
    {
        $this->roomDepository = $roomDepository;
    }

    public function readMany (array $inputs)
    {
        return $this->roomDepository->pluck(['id'], [], [], [], [['filter', $inputs]]);
    }
}