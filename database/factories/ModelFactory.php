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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
    ];
});
$factory->define(App\Person::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->first_name,
        'last_name' => $faker->last_name,
        'email' => $faker->email,
        'phone' => $faker->phone,
    ];
});

$factory->define(App\Order::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Ticket::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\OrderItem::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Church::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->company,
    ];
});

$factory->define(App\Organization::class, function (Faker\Generator $faker) {
    return [
        'slug' => $faker->unique()->slug,
        'church_id' => function () {
            return factory(App\Church::class)->create()->id;
        },
    ];
});
