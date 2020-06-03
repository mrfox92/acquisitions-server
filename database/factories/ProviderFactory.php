<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Provider;
use Faker\Generator as Faker;

$factory->define(Provider::class, function (Faker $faker) {
    return [
        'rut'       =>  $faker->unique()->randomDigit,
        'name'      =>  $faker->name,
        'address'   =>  $faker->address,
        'url_web'   =>  $faker->url,
        'phone'     =>  $faker->phoneNumber,    //  el campo debe ser string
        'email'     =>  $faker->unique()->safeEmail,
    ];
});
