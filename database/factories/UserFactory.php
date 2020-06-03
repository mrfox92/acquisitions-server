<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {

    $name = $faker->name;
    $last_name = $faker->lastName;
    $slug = $name . " " . $last_name;
    
    return [
        'role_id'   =>  \App\Role::all()->random()->id,
        'name' => $name,
        'last_name' =>  $last_name,
        'slug'      =>  Str::slug($slug, '-'),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => bcrypt('test1234'),
        'remember_token' => Str::random(10),
        'avatar'    =>  \Faker\Provider\Image::image(storage_path() . '/app/public/users', 200, 200, 'people', false),
    ];
});
