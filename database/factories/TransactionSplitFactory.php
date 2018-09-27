<?php

use Faker\Generator as Faker;

$factory->define(App\TransactionSplit::class, function (Faker $faker) {
    return [
        'order_id' => factory(App\Order::class)->create(),
        'amount' => $faker->randomNumber(2) * 100,
    ];
});
