<?php

use Faker\Generator as Faker;

$factory->define(App\Transaction::class, function (Faker $faker) {
    return [
        'source' => $faker->randomElement(['stripe', 'other']),
        'identifier' => str_random(60),
        'cc_brand' => $faker->creditCardType,
        'cc_last4' => $faker->creditCardNumber,
    ];
});
