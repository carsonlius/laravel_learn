<?php

use Faker\Generator as Faker;

$factory->define(\App\Post::class, function (Faker $faker) {

    $list_ids = \App\User::all()->pluck('id');

    return [
        'title' => $faker->title(),
        'body' => $faker->paragraph(),
        'user_id' => $faker->randomElement($list_ids),
        'active' => $faker->boolean()
    ];
});
