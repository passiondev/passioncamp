<?php

namespace App\Console\Commands;

use App\Item;
use App\Organization;
use Illuminate\Console\Command;
use Illuminate\Support\LazyCollection;
use League\Csv\Reader;

class ImportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:products';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $csv = Reader::createFromPath(storage_path('app/2020/products.csv'));
        $csv->setHeaderOffset(0);

        // collect($csv->getRecords());
        LazyCollection::make(function () use ($csv) {
            foreach ($csv->getRecords() as $row) {
                yield $row;
            }
        })
            ->each(function ($row) {
                $organization = Organization::findOrFail($row['ORG ID']);

                $organization->items()->create([
                    'org_type' => $type = $this->isATicketItem($row['product']) ? 'ticket' : 'hotel',
                    'item_id' => Item::firstOrCreate(['name' => $row['product']], ['type' => $type])->id,
                    'cost' => (int) $row['price'] * 100,
                    'quantity' => $row['quantity'],
                    'notes' => $row['notes'],
                ]);

                // if ($row['notes']) {
                //     $organization->addNote($row['notes']);
                // }
            });
    }

    public function isATicketItem($product)
    {
        $tickets = [
            'Full',
            'Program + Meals',
            'Program + Hotel',
            'Program Only',
        ];

        if (\in_array($product, $tickets, true)) {
            return true;
        }

        return false;
    }
}
