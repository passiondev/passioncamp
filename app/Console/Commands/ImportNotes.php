<?php

namespace App\Console\Commands;

use App\Organization;
use Illuminate\Console\Command;
use Illuminate\Support\LazyCollection;
use League\Csv\Reader;

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
        $csv = Reader::createFromPath(storage_path('app/2020/notes.csv'));
        $csv->setHeaderOffset(0);

        LazyCollection::make(function () use ($csv) {
            foreach ($csv->getRecords() as $row) {
                yield $row;
            }
        })
            ->each(function ($row) {
                $organization = Organization::findOrFail($row['ORG ID']);

                $organization->addNote($row['notes']);
            });
    }
}
