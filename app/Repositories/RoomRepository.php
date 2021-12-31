<?php

namespace App\Repositories;

use App\Models\Room;

class RoomRepository implements Contracts\RoomRepositoryContract
{

    public function findAll ()
    {
        return Room::get(['id', 'name']);
    }
}