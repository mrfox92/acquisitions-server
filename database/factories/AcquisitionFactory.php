<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Acquisition;
use Faker\Generator as Faker;

$factory->define(Acquisition::class, function (Faker $faker) {
    return [
        'user_id'   =>  null,
    ];
});
