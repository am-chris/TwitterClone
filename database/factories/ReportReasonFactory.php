<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Report\Reason::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->sentence,
    ];
});
