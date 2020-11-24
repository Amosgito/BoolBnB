<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Image;
use Faker\Generator as Faker;

$factory->define(Image::class, function (Faker $faker) {
    return [
        
        'img' => $faker -> imageUrl($width = 640, $height = 480), // 'http://lorempixel.com/640/480/'

    ];
});
