<?php

namespace App\Repositories;

use App\Booking;
use App\Http\Requests\Api\User\BookingCreateRequest;
use App\Notifications\BookingCreateNotification;
use App\Services\ResponseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class BookingRepository
{
    /**
     * BookingRepository store booking method.
     *
     * @param  BookingCreateRequest $request
     * @param  boolean $isJson
     * @return object
     */
    public function store(BookingCreateRequest $request, $isJson = false)
    {
        $booking = new Booking();
        $booking->user_id = Auth::id();
        $booking->room_id = $request->input('room_id');
        $booking->total_person = $request->input('total_person');
        $booking->booking_time = $request->input('meeting_date') . ' ' . $request->input('meeting_time');
        $booking->noted = (! empty($request->input('noted'))) ? $request->input(['noted']) : '';
        if ($booking->save()) {
            Notification::send(Auth::user(), new BookingCreateNotification([
                'room_name' => $booking->room->room_name,
                'booking_time' => $booking->booking_time,
                'total_person' => $booking->total_person,
                'noted' => $booking->noted,
                'subject' => 'Booking Information Meet.com',
                'title' => 'Here is your detail booking meeting room information'
            ]));
            return ($isJson) ? ResponseService::success($booking) : $booking;
        }
        return ResponseService::failure('Failed to create booking.');
    }

    /**
     * BookingRepository get today booking list method.
     *
     * @return array
     */
    public function getTodayBookingList()
    {
        return DB::table('bookings')
                    ->leftJoin('users', 'users.id', '=', 'bookings.user_id')
                    ->leftJoin('rooms', 'rooms.id', '=', 'bookings.room_id')
                    ->whereBetween('booking_time', [
                        date('Y-m-d 00:00:01'),
                        date('Y-m-d 23:59:59')
                    ])
                    ->select(
                        'bookings.booking_time', 'bookings.total_person', 'bookings.noted',
                        'users.id as user_id', 'users.email', 'rooms.room_name'
                    )
                    ->get()
                    ->toArray();
    }
}
