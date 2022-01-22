<?php

namespace App\Repositories;

use App\Models\Room;
use App\Repositories\Abstracts\BaseRepository;

class RoomRepository extends BaseRepository implements Contracts\RoomRepositoryContract
{
    function model () : string
    {
        return Room::class;
    }
}