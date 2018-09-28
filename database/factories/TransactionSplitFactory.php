<?php

use Faker\Generator as Faker;

$factory->define(App\TransactionSplit::class, function (Faker $faker) {
    return [
        'transaction_id' => factory(App\Transaction::class)->create(),
        'amount' => $faker->randomNumber(2) * 100,
    ];
});
