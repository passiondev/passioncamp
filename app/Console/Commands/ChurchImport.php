<?php

namespace App\Console\Commands;

use App\Church;
use App\Person;
use App\Organization;
use League\Csv\Reader;
use Illuminate\Console\Command;

class ChurchImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import churches';

    /**
     * Create a new command instance.
     *
     * @return void
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
        $file = storage_path('app/import.csv');
        $reader = Reader::createFromPath($file);
        $keys = $reader->fetchOne();
        $results = $reader->fetchAssoc($keys);
        $collection = collect($results)->slice(1);

        $collection->each(function ($data) {
            $organization = new Organization;
            $organization->church()->associate(Church::create([
                'name' => $data['church_name'],
                'street' => $data['church_street'],
                'city' => $data['church_city'],
                'state' => $data['church_state'],
                'zip' => $data['church_zip'],
                'website' => $data['church_website'],
                'pastor_name' => $data['church_pastor'],
            ]));
            $organization->contact()->associate(Person::create([
                'first_name' => $data['contact_first'],
                'last_name' => $data['contact_last'],
                'phone' => $data['contact_phone1'],
                'phone2' => $data['contact_phone2'],
                'email' => $data['contact_email'],
            ]));
            $organization->studentPastor()->associate(Person::create([
                'first_name' => $data['pastor_first'],
                'last_name' => $data['pastor_last'],
                'phone' => $data['pastor_phone1'],
                'phone2' => $data['pastor_phone2'],
                'email' => $data['pastor_email'],
            ]));
            $organization->save();
        });

        dd($collection);
    }
}
