<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Provider;
use Faker\Generator as Faker;

$factory->define(Provider::class, function (Faker $faker) {
    return [
        'rut'       =>  $faker->uuid,   //  identificador unico
        'name'      =>  $faker->name,
        'address'   =>  $faker->streetAddress,
        'url_web'   =>  $faker->url,
        'phone'     =>  $faker->e164PhoneNumber,    //  el campo debe ser string
        'email'     =>  $faker->unique()->safeEmail,
    ];
});
