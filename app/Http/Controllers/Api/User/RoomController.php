<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Repositories\RoomRepository;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    private $roomRepository;

    public function __construct()
    {
        $this->roomRepository = new RoomRepository;
    }

    /**
     * RoomController get available rooms method.
     *
     * @param  Request $request
     * @return object
     */
    public function getAvailableRooms(Request $request)
    {
        $request->validate(['date' => 'date_format:Y-m-d']);
        return $this->roomRepository->getAvailableRooms([
            'date' => $request->query('date')
        ]);
    }
}
