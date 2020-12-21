<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'id' => (int) mt_rand(),
        'email' => 'user@meet.com',
        'password' => Hash::make('user'),
        'photo' => 'https://cdn.iconscout.com/icon/free/png-512/avatar-370-456322.png',
    ];
});
