<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Dispatcher;
use Faker\Generator as Faker;

$factory->define(Dispatcher::class, function (Faker $faker) {
    return [
        'user_id'   =>  null,
    ];
});
