<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Invoice;
use Faker\Generator as Faker;

$factory->define(Invoice::class, function (Faker $faker) {

    $startingDate = $faker->dateTime('now');
    $endingDate   = $faker->dateTimeBetween('now', '+1 month');

    return [
        'invoice_number'    =>  $faker->numberBetween(1, 1000),
        'provider_id'       =>  \App\Provider::all()->random()->id,
        'acquisition_id'    =>  \App\Acquisition::all()->random()->id,    
        'emission_date'     =>  $startingDate,
        'expiration_date'   =>  $endingDate
    ];
});
