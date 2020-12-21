<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\BookingCreateRequest;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    private $bookingService;

    public function __construct()
    {
        $this->bookingService = new BookingService;
    }

    /**
     * BookingController create booking method.
     *
     * @param  BookingCreateRequest $request
     * @return object
     */
    public function create(BookingCreateRequest $request)
    {
        $request->validated();
        return $this->bookingService->createBooking($request);
    }

    /**
     * BookingController send today booking notification check in reminder method.
     *
     * @return object
     */
    public function sendTodayBookingNotification()
    {
        return $this->bookingService->sendTodayBookingNotification();
    }

    /**
     * BookingController check in or check out method.
     *
     * @param  Request $request
     * @return object
     */
    public function checkInOutBookingRoomUser(Request $request)
    {
        $request->validate([
            'booking_id' => 'required | numeric',
            'action' => Rule::in(['checkin', 'checkout'])
        ]);
        return $this->bookingService->checkInOut([
            'booking_id' => $request->input('booking_id'),
            'action' => (! empty($request->input('action'))) ? $request->input('action') : 'checkin'
        ]);
    }
}
