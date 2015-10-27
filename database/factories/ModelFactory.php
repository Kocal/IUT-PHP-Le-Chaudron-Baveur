<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Items::class, function (Faker\Generator $faker) {
    $name = '';
    $i = 0;

    for($i = 0; $i < rand(2, 5); $i++) {
        $name .= str_random(rand(3, 10)) . ' ';
    }

    return [
        'user_id' => mt_rand(1, 3),
        'category_id' => mt_rand(1, 7),
        'name' => trim($name),
        'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. A quibusdam sed sequi voluptatibus. A beatae culpa eius eos labore magnam magni, minima nobis nulla provident qui quis quisquam, sit totam.',
        'photo' => '',
        'price' => mt_rand(500, 10000) / 100,
        'date_start' => '2015-10-' . mt_rand(24, 26),
        'date_end' => '2015-' . mt_rand(11, 12) . '-' . mt_rand(1, 30)
    ];
});
