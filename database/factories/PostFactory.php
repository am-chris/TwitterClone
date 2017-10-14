<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Post::class, function (Faker $faker) {
    return [
        'user_id' => rand(1, 20),
        'content' => $faker->paragraph,
    ];
});
