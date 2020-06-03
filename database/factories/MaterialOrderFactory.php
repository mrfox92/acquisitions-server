<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\MaterialOrder;
use Faker\Generator as Faker;

$factory->define(MaterialOrder::class, function (Faker $faker) {
    return [
        'material_id'   =>  \App\Material::all()->random()->id,
        'order_id'      =>  \App\Order::all()->random()->id,
        'quantity'      =>  $faker->numberBetween(1, 20)
    ];
});
