<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Contracts\RoomServiceContract;

class RoomController extends Controller
{
    private RoomServiceContract $roomService;

    /**
     * @param RoomServiceContract $roomService
     */
    public function __construct (RoomServiceContract $roomService)
    {
        $this->roomService = $roomService;
    }

    public function read (Request $request)
    {
        return response(['data' => $this->roomService->read()]);
    }
}
