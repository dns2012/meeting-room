<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\RoomCreateRequest;
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
     * RoomController create room method.
     *
     * @param  RoomCreateRequest $request
     * @return object
     */
    public function create(RoomCreateRequest $request)
    {
        $request->validated();
        return $this->roomRepository->store($request, true);
    }
}
