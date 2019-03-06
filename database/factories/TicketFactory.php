<?php

use Faker\Generator as Faker;

$factory->define(App\Ticket::class, function (Faker $faker) {
    return [
        'person_id' => function () {
            return factory(App\Person::class)->create()->id;
        },
        'price' => $faker->numberBetween(100, 999) * 100,
    ];
});
