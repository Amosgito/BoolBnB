<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Apartment;
use App\Model;
use Faker\Generator as Faker;

$factory->define(Apartment::class, function (Faker $faker) {
    return [
        
        'description' => $faker -> text($maxNbChars = 100),
        'title'       => $faker -> word(), 
        'address'     => $faker -> address(),
        'room_qt'     => $faker -> numberBetween($min = 2, $max = 5),
        'bathroom_qt' => $faker -> numberBetween($min = 1, $max = 3),
        'bed_qt'      => $faker -> numberBetween($min = 2, $max = 6),
        'mq'          => $faker -> numberBetween($min = 50, $max = 300),
        'visible'     => $faker -> boolean($chanceOfGettingTrue = 50),
        'latitude'    => $faker -> latitude($min = -90, $max = 90),
        'longitude'   => $faker -> longitude($min = -180, $max = 180)

    ];
});
