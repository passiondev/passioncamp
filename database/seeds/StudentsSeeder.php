<?php

use App\User;
use App\Order;
use App\Church;
use App\Ticket;
use App\Organization;
use App\TransactionSplit;
use Illuminate\Database\Seeder;

class StudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->call(DatabaseSeeder::class);
        // create two churches - HS, MS
        $ms = factory(Organization::class)->create([
            'slug' => 'ww2019ms',
            'church_id' => factory(Church::class)->create([
                'name' => 'Winter WKND (MS)',
            ]),
        ]);

        $hs = factory(Organization::class)->create([
            'slug' => 'ww2019hs',
            'church_id' => factory(Church::class)->create([
                'name' => 'Winter WKND (HS)',
            ]),
        ]);

        $user = factory(User::class)->create([
            'email' => 'matt@example.com',
        ]);

        $user->orders()->saveMany(
            factory(Order::class, 3)->make([
                'user_id' => null,
                'organization_id' => function () use ($hs, $ms) {
                    return collect([$hs, $ms])->random()->getKey();
                },
            ])
        )->each(function ($order) {
            $order->tickets()->saveMany(
                factory(Ticket::class, range(1, 9)[array_rand(range(1, 9))])->make()
            );

            $order->transactions()->saveMany(
                factory(TransactionSplit::class, range(1, 10)[array_rand(range(1, 10))])->make()
            );
        });

        factory(Order::class)
            ->create([
                'organization_id' => $ms,
                'user_id' => $user,
            ])
            ->tickets()->saveMany(
                factory(Ticket::class, 3)->make()
            );

        factory(Order::class)
            ->create([
                'organization_id' => $hs,
                'user_id' => $user,
            ])
            ->tickets()->saveMany(
                factory(Ticket::class, 5)->make()
            );
    }
}
