<?php

use Faker\Generator as Faker;

$factory->define(App\Organization::class, function (Faker $faker) {
    return [
        'church_id' => function () {
            return factory(App\Church::class)->create()->id;
        },
    ];
});
