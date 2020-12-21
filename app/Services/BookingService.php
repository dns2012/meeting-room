<?php

namespace App\Services;

use App\Booking;
use App\Http\Requests\Api\User\BookingCreateRequest;
use App\Notifications\BookingCreateNotification;
use App\Repositories\BookingRepository;
use App\Room;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class BookingService
{
    private $bookingRepository;

    public function __construct()
    {
        $this->bookingRepository = new BookingRepository;
    }
    /**
     * BookingService get booked room ids.
     *
     * Here i use manual checking step, because from the task there is uncertain / unclear information
     * about how we set the checkout time. However when the case is hotel / guest house it should
     * be add checkout time first. But this meeting room seems meeting room will add checkout time after
     * meeting completed. So it's little confusing when booking a room but we still dont know meeting
     * completed or not, that's why here can't using single query to check availability.
     *
     * @param  array $data ex: ['date' => '2020-12-12']
     * @return array
     */
    public function getBookedRoomIds($data)
    {
        $startTime = (! empty($data['date'])) ? $data['date'] . ' 00:00:01' : date('Y-m-d 00:00:01');
        $endTime = (! empty($data['date'])) ? $data['date'] . ' 23:59:59' : date('Y-m-d 23:59:59');
        $rooms = DB::table('rooms')->select('id')->get();
        $roomIds = [];
        foreach ($rooms as $room) {
            $roomIds[] = $room->id;
        }
        $bookings = DB::table('bookings')
            ->whereBetween('booking_time', [$startTime, $endTime])
            ->whereIn('room_id', $roomIds)
            ->select('room_id', 'check_out_time')
            ->get();
        $preventRooms = [];
        foreach($bookings as $booking) {
            if (empty($booking->check_out_time)) {
                $preventRooms[] = $booking->room_id;
            }
        }
        return $preventRooms;
    }

    /**
     * BookingService create booking method.
     *
     * @param  BookingCreateRequest $request
     * @return object
     */
    public function createBooking(BookingCreateRequest $request)
    {
        $roomId = $request->input('room_id');
        $room = Room::find($roomId);
        if (! $room instanceof Room) {
            return ResponseService::failure('Room not found.');
        }
        $getBookedIds = $this->getBookedRoomIds(['date' => $request->input('meeting_date')]);
        if (in_array($roomId, $getBookedIds)) {
            return ResponseService::failure('Room already booked this time.');
        }
        $totalPerson = $request->input('total_person');
        if ($totalPerson > $room->room_capacity) {
            return ResponseService::failure("Maximum room capacity is only for $room->room_capacity people");
        }
        return $this->bookingRepository->store($request, true);
    }

    /**
     * BookingService send today booking notification check in reminder method.
     *
     * @return object
     */
    public function sendTodayBookingNotification()
    {
        $bookings = $this->bookingRepository->getTodayBookingList();
        if ($bookings) {
            foreach ($bookings as $booking) {
                Notification::route('mail', $booking->email)
                                ->notify(new BookingCreateNotification([
                                    'room_name' => $booking->room_name,
                                    'booking_time' => $booking->booking_time,
                                    'total_person' => $booking->total_person,
                                    'noted' => $booking->noted,
                                    'subject' => 'Booking Reminder Meet.com',
                                    'title' => 'Check in your booked room today !'
                                ]));
            }
            return ResponseService::success('Check in notification sent.');
        }
        return ResponseService::success('No reminder today, just relax.');
    }

    /**
     * BookingService check in or check out method.
     *
     * @param  array $data
     * @return object
     */
    public function checkInOut($data)
    {
        $booking = Booking::where('user_id', Auth::id())
                            ->where('id', $data['booking_id'])
                            ->where('check_out_time', null)
                            ->orderBy('id', 'DESC')
                            ->first();
        if (! $booking instanceof Booking) {
            return ResponseService::failure('Booking information not found, check your Booking ID.');
        }
        $currentTime = date('Y-m-d H:i:s');
        if ($data['action'] == 'checkin') {
            $booking->check_in_time = $currentTime;
            $emailSubject = 'Check In Information Meet.com';
            $emailTitle = 'Now that you are allowed to use the room, enjoy your meet';
            if (! empty($booking->check_in_time)) {
                return ResponseService::failure('You already check in.');
            }
        } else {
            $booking->check_out_time = $currentTime;
            $emailSubject = 'Check Out Information Meet.com';
            $emailTitle = 'Great, thank you for your participation';
        }
        if ($booking->save()) {
            Notification::send(Auth::user(), new BookingCreateNotification([
                'room_name' => $booking->room->room_name,
                'booking_time' => $booking->booking_time,
                'total_person' => $booking->total_person,
                'noted' => $booking->noted,
                'subject' => $emailSubject,
                'title' => $emailTitle
            ]));
            return ResponseService::success($booking);
        }
        return ResponseService::failure('Failed to send checkin notification.');
    }
}
