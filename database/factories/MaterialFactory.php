<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Material;
use Faker\Generator as Faker;

$factory->define(Material::class, function (Faker $faker) {
    
    $name = $faker->sentence;
    $unity_type = $faker->randomElement([
        \App\Material::UNITY,
        \App\Material::PACKAGE,
        \App\Material::REAM,
        \App\Material::SET,
        \App\Material::BOX
    ]);

    return [
        'bar_code'          =>  $faker->isbn13,
        'acquisition_id'    =>  \App\Acquisition::all()->random()->id,
        'name'              =>  $name,
        'slug'              =>  Str::slug($name, '-'),
        'unity_type'        =>  $unity_type,
        'stock'             =>  $faker->randomDigit,
        'picture'           =>  \Faker\Provider\Image::image(storage_path() . '/app/public/materials', 600, 350, 'tech', false),
    ];
});
