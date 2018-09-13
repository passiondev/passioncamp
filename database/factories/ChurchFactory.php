<?php

use Faker\Generator as Faker;

$factory->define(App\Church::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
    ];
});
