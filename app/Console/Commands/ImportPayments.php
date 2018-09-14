<?php

namespace App\Console\Commands;

use App\Organization;
use League\Csv\Reader;
use Illuminate\Console\Command;

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
        $csv = Reader::createFromPath(storage_path('app/2019/payments.csv'));
        $csv->setHeaderOffset(0);

        collect($csv->getRecords())
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
