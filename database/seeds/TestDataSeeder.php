<?php

use App\Hotel;
use App\Item;
use App\Order;
use App\Organization;
use App\OrgItem;
use App\Ticket;
use App\TransactionSplit;
use App\User;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $org = factory(Organization::class)->create();

        factory(User::class)->states(['churchAdmin'])->create([
            'email' => 'church@example.com',
            'organization_id' => $org,
        ]);

        $org->tickets()->save(
            factory(OrgItem::class)->make([
                'item_id' => factory(Item::class)->create([
                    'type' => 'ticket',
                    'name' => 'Tcket',
                ]),
                'org_type' => 'ticket',
                'cost' => 39900,
                'quantity' => 30,
            ])
        );

        $org->hotelItems()->save(
            factory(OrgItem::class)->make([
                'item_id' => factory(Hotel::class)->create(),
                'org_type' => 'hotel',
                'cost' => 0,
                'quantity' => 6,
            ])
        );

        factory(TransactionSplit::class, 2)->create([
            'organization_id' => $org,
        ]);

        $org->orders()->save(
            $order = factory(Order::class)->make([
                'organization_id' => null,
            ])
        );

        $order->tickets()->saveMany(
            factory(Ticket::class, 3)->make()
        );
    }
}
