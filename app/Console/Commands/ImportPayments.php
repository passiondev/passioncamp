<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use League\Csv\Reader;
use App\Organization;

class ImportPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:payments';

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

                $organization->addTransaction([
                    'source' => $row['source'],
                    'identifier' => $row['identifier'],
                    'amount' => $row['amount'] * 100,
                ]);
            });
    }
}
