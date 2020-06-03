<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\MaterialInvoice;
use Faker\Generator as Faker;

$factory->define(MaterialInvoice::class, function (Faker $faker) {

    $quantity = $faker->numberBetween(1, 100);
    $unity_cost = $faker->numberBetween(10, 500);
    $total_cost = ($quantity * $unity_cost);
    $iva =  ($total_cost * 0.19);
    return [
        'material_id'   =>  \App\Material::all()->random()->id,
        'invoice_id'    =>  \App\Invoice::all()->random()->id,
        'quantity'      =>  $quantity,
        'unity_cost'    =>  $unity_cost,
        'iva'           =>  $iva,
        'total_cost'    =>  $total_cost
    ];
});
