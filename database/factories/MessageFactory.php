<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Message;
use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {
    return [
        'message' => $faker -> text($maxNbChars = 200),
        'email'   => $faker -> email(),
        'read'    => $faker -> boolean($chanceOfGettingTrue = 50),
        
    ];
});
