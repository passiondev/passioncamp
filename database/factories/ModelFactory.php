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
        'email' => $faker->safeEmail,
        'person_id' => function ($self) {
            return factory(App\Person::class)->create([
                'email' => $self['email'],
            ])->id;
        }
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

$factory->define(App\Person::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->safeEmail,
        'phone' => $faker->phoneNumber,
    ];
});

$factory->define(App\Order::class, function (Faker\Generator $faker) {
    return [
        'organization_id' => function () {
            return factory(App\Organization::class)->create()->id;
        }
    ];
});

$factory->define(App\Ticket::class, function (Faker\Generator $faker) {
    return [
        'person_id' => function () {
            return factory(App\Person::class)->create()->id;
        },
        'order_id' => function () {
            return factory(App\Order::class)->create()->id;
        }
    ];
});

$factory->define(App\OrderItem::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\OrgItem::class, function (Faker\Generator $faker) {
    return [
    ];
});

$factory->define(App\Item::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->words(3, true)
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
