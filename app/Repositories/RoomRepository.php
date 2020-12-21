<?php

namespace App\Repositories;

use App\Http\Requests\Api\Admin\RoomCreateRequest;
use App\Room;
use App\Services\BookingService;
use App\Services\ImageService;
use App\Services\ResponseService;
use Illuminate\Support\Facades\DB;

class RoomRepository
{
    private $bookingService;

    public function __construct()
    {
        $this->bookingService = new BookingService;
    }
    /**
     * RoomRepository store room method.
     *
     * @param  RoomCreateRequest $request
     * @param  bool $isJson
     * @return object
     */
    public function store(RoomCreateRequest $request, $isJson = false)
    {
        $room = new Room();
        $room->room_name = $request->input('room_name');
        $room->room_capacity = $request->input('room_capacity');
        $room->photo = ImageService::upload($request->file('photo'));
        if ($room->save()) {
            return ($isJson) ? ResponseService::success($room) : $room;
        }
        return ResponseService::failure('Failed to create room.');
    }

    /**
     * RoomRepository get available room method.
     *
     * @param  array $data ex: ['date' => '2020-12-12']
     * @return array
     */
    public function getAvailableRooms($data)
    {
        $availableRooms = DB::table('rooms')
                            ->whereNotIn('id', array_unique($this->bookingService->getBookedRoomIds($data)))
                            ->get();
        return ResponseService::success($availableRooms);
    }
}
