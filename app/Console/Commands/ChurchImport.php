<?php

namespace App\Console\Commands;

use App\Organization;
use Illuminate\Console\Command;
use League\Csv\Reader;

class ChurchImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:churches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import churches';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $csv = Reader::createFromPath(storage_path('app/2020/church.csv'));
        $csv->setHeaderOffset(0);

        $keys = collect($csv->getRecords())
            ->reject(function ($row) {
                return ! empty($row['new id']);
            })
            ->map(function ($row) {
                $organization = new Organization([
                    'id' => $row['ORG ID'],
                ]);

                $church = tap($organization->church->fill([
                    'name' => $row['Church Name'],
                    'street' => $row['Address'],
                    'city' => $row['City'],
                    'state' => $row['State'],
                    'zip' => $row['Zip'],
                    'website' => $row['Website'],
                    'pastor_name' => $row['Pastor'],
                ]), function ($church) {
                    $church->save();
                });
                $organization->church()->associate($church);

                $studentPastor = tap($organization->studentPastor->fill([
                    'first_name' => $row['Student Pastor First'],
                    'last_name' => $row['Student Pastor Last'],
                    'email' => $row['SP Email'],
                    'phone' => $row['SP Mobile'],
                    'phone2' => $row['SP Office'],
                ]), function ($studentPastor) {
                    $studentPastor->save();
                });
                $organization->studentPastor()->associate($studentPastor);

                $contact = tap($organization->contact->fill([
                    'first_name' => $row['Contact First'],
                    'last_name' => $row['Contact Last'],
                    'email' => $row['Contact Email'],
                    'phone' => $row['Contact Mobile'],
                    'phone2' => $row['Contact Office'],
                ]), function ($contact) {
                    $contact->save();
                });

                $organization->contact()->associate($contact);

                $organization->save();

                $organization->users()->create([
                    'name' => sprintf('%s %s', $row['Contact First'], $row['Contact Last']),
                    'email' => $row['Contact Email'],
                ]);

                // $organization->addNote($row['Notes']);

                return $organization->id;
            })
            ->dd();

        die();
    }
}
