<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Booking;
use App\User;
use Faker\Generator as Faker;

$factory->define(Booking::class, function (Faker $faker) {
    $possibleId = [1, 2, 3, 4, 5];
    $possibleBookingTime = ['+1 days', '+2 days', '+3 days'];
    return [
        'id' => (int) mt_rand(),
        'user_id' => User::first()->id,
        'room_id' => $possibleId[array_rand($possibleId)],
        'total_person' => 20,
        'booking_time' => date('Y-m-d H:i:s', strtotime($possibleBookingTime[array_rand($possibleBookingTime)])),
        'noted' => 'Booked',
        'check_in_time' => null,
        'check_out_time' => null
    ];
});
