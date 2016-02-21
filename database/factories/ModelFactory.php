<?php

use App\User;
use App\Order;
use App\Person;
use App\Ticket;
use App\OrderItem;

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

$factory->define(User::class, function (Faker\Generator $faker) {
    return [
    ];
});
$factory->define(Person::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->first_name,
        'last_name' => $faker->last_name,
        'email' => $faker->email,
        'phone' => $faker->phone,
    ];
});

$factory->define(Order::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(Ticket::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(OrderItem::class, function (Faker\Generator $faker) {
    return [
    ];
});
