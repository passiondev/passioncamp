<?php

namespace App\Console\Commands;

use App\Organization;
use Illuminate\Console\Command;
use Illuminate\Support\LazyCollection;
use League\Csv\Reader;

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
        $csv = Reader::createFromPath(storage_path('app/2020/payments.csv'));
        $csv->setHeaderOffset(0);

        LazyCollection::make(function () use ($csv) {
            foreach ($csv->getRecords() as $row) {
                yield $row;
            }
        })
            ->each(function ($row) {
                $organization = Organization::findOrFail($row['ORG ID']);

                $organization->addTransaction([
                    'source' => $row['source'],
                    'identifier' => $row['identifier'],
                    'amount' => (int) $row['amount'] * 100,
                ]);
            });
    }
}
