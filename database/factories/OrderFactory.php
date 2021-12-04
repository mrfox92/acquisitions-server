<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {

    $name = $faker->sentence;

    $status = $faker->randomElement([
        \App\Order::ENABLED,
        \App\Order::PROCESING,
        \App\Order::FINISHED,
        \App\Order::CANCELED
    ]);

    return [
        'dispatcher_id'     =>  \App\Dispatcher::all()->random()->id,
        'status'            =>  $status,
        'name_responsible'  =>  $name
    ];
});
