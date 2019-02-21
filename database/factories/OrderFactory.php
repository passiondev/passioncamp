<?php

use Faker\Generator as Faker;

$factory->define(App\Order::class, function (Faker $faker) {
    return [
        'organization_id' => function () {
            return factory(App\Organization::class)->create()->id;
        },
        'user_id' => function ($data) {
            return factory(App\User::class)->create([
                'organization_id' => $data['organization_id'],
            ])->id;
        },
    ];
});
