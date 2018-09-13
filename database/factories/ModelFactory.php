<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Person::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->safeEmail,
        'phone' => $faker->phoneNumber,
        'gender' => $faker->randomElement(['M', 'F']),
    ];
});

$factory->define(App\Order::class, function (Faker\Generator $faker) {
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

$factory->define(App\OrderItem::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Item::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->words(3, true),
    ];
});

$factory->define(App\Room::class, function ($faker) {
    return [
        'organization_id' => function () {
            return factory(App\Organization::class)->create()->id;
        },
        'name' => 'Room',
    ];
});

$factory->define(App\Hotel::class, function ($faker) {
    return [
        'name' => 'Hotel',
    ];
});

$factory->define(App\Waiver::class, function ($faker) {
    return [
        'ticket_id' => function () {
            return factory(App\Ticket::class)->create()->id;
        },
        'provider' => 'adobesign',
        'provider_agreement_id' => $faker->md5,
        'status' => 'pending',
    ];
});
