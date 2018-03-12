<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use League\Csv\Reader;
use App\Organization;

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
        $csv = Reader::createFromPath(storage_path('app/import2018-products.csv'));
        $csv->setHeaderOffset(0);

        collect($csv->getRecords())
            ->each(function ($row) {
                $organization = Organization::findOrFail($row['ORG ID']);

                $organization->items()->create([
                    'org_type' => in_array($row['item_id'], [1,3,4,5,19]) ? 'ticket' : 'hotel',
                    'item_id' => $row['item_id'],
                    'cost' => $row['price'] * 100,
                    'quantity' => $row['quantity'],
                ]);
            });
    }
}
