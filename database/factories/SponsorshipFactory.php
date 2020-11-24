<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Sponsorship;
use Faker\Generator as Faker;

$factory->define(Sponsorship::class, function (Faker $faker) {
    return [
        'start_date' => $faker -> dateTimeBetween($startDate = '-15 days', $endDate = '+15 days', $timezone = null),
        'end_date'   => $faker -> dateTimeBetween($startDate = '-15 days', $endDate = '+15 days', $timezone = null),
    ];
});
