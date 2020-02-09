<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(App\Transaction::class, function (Faker $faker) {
    return [
        'source' => $faker->randomElement(['stripe', 'other']),
        'identifier' => Str::random(60),
        'cc_brand' => $faker->creditCardType,
        'cc_last4' => $faker->creditCardNumber,
    ];
});
