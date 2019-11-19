<?php

namespace App\Console\Commands;

use App\Organization;
use League\Csv\Reader;
use Illuminate\Console\Command;

class ImportNotes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:notes';

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

                $organization->addNote($row['note']);
            });
    }
}
