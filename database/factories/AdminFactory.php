<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Admin;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(Admin::class, function (Faker $faker) {
    return [
        'id' => (int) mt_rand(),
        'name' => 'admin',
        'email' => 'admin@meet.com',
        'email_verified_at' => now(),
        'password' => Hash::make('admin')
    ];
});
