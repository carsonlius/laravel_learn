<?php

use Faker\Generator as Faker;

$factory->define(\App\Project::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'body' => $faker->paragraph(),
        'desc' => $faker->word
    ];
});
