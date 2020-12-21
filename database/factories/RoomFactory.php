<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Room;
use Faker\Generator as Faker;

$factory->define(Room::class, function (Faker $faker) {
    return [
        'room_name'     =>  "$faker->lastName Room",
        'room_capacity' =>  rand(0, 20),
        'photo'         =>  'https://png.pngtree.com/png-clipart/20190725/ourmid/pngtree-business-office-meeting-room-rotating-seat-png-image_1046444.jpg'
    ];
});
