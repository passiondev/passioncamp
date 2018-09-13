<?php

$factory->define(App\User::class, function ($faker) {
    return [
        'email' => $faker->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'person_id' => function ($data) {
            return factory(App\Person::class)->create([
                'email' => $data['email'],
            ])->id;
        },
    ];
});

$factory->state(App\User::class, 'superAdmin', function ($faker) {
    return [
        'access' => 100,
        'organization_id' => null,
    ];
});

$factory->state(App\User::class, 'churchAdmin', function ($faker) {
    return [
        'access' => 1,
        'organization_id' => function () {
            return factory(App\Organization::class)->create()->id;
        },
    ];
});
