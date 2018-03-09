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
        $csv = Reader::createFromPath(storage_path('app/import2018.csv'));
        $csv->setHeaderOffset(0);

        $keys = collect($csv->getRecords())
            ->map(function ($row) {
                $organization = Organization::findOrNew($row['ORG ID']);

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

                return $organization->id;
            })
            ->dd()
        ;

        die();

        $tickets = [
            'full' => 1,
            'meals' => 3,
            'lodging' => 4,
            'program' => 5,
            'hotel' => 19,
        ];

        // Organization::find(xx)->items()->create([
        //     'quantity' => xx,
        //     'cost' => xxx * 100,
        //     'item_id' => $tickets['full'],
        //     'org_type' => 'ticket'
        // ]);

        $data = [
            [6,8,379, $tickets['full']],
            [12,14,379, $tickets['full']],
            [70,30,319, $tickets['full']],
            [19,24,379, $tickets['full']],
            [68,23,399, $tickets['full']],
            [24,14,379, $tickets['full']],
            [36,13,379, $tickets['full']],
            [27,90,399, $tickets['full']],
            [66,20,399, $tickets['full']],
            [64,100,399, $tickets['full']],
            [61,45,399, $tickets['full']],
            [13,16,399, $tickets['full']],
            [28,24,399, $tickets['full']],
            [35,80,399, $tickets['full']],
            [18,44,379, $tickets['full']],
            [3,40,379, $tickets['full']],
            [59,75,399, $tickets['full']],
            [45,24,389, $tickets['full']],
            [5,36,379, $tickets['full']],
            [9,10,379, $tickets['full']],
            [67,96,399, $tickets['full']],
            [62,28,399, $tickets['full']],
            [39,20,389, $tickets['full']],
            [69,27,399, $tickets['full']],
            [21,20,379, $tickets['full']],
            [25,17,379, $tickets['full']],
            [1,600,369, $tickets['full']],
            [31,36,399, $tickets['full']],
            [32,111,399, $tickets['full']],
            [33,50,399, $tickets['full']],
            [30,303,399, $tickets['full']],
            [34,36,399, $tickets['full']],
            [44,43,389, $tickets['full']],
            [40,30,389, $tickets['full']],
            [8,19,379, $tickets['full']],
            [60,28,399, $tickets['full']],
            [43,13,399, $tickets['full']],
            [11,16,379, $tickets['full']],
            [71,28,329, $tickets['hotel']],
            [15,30,309, $tickets['hotel']],
            [65,59,319, $tickets['hotel']],
            [7,11,299, $tickets['hotel']],
            [46,60,319, $tickets['hotel']],
            [14,20,319, $tickets['hotel']],
            [26,25,299, $tickets['hotel']],
            [38,11,319, $tickets['hotel']],
            [10,62,299, $tickets['hotel']],
            [50,20,255, $tickets['meals']],
            [54,60,255, $tickets['meals']],
            [49,70,255, $tickets['meals']],
            [55,70,175, $tickets['program']],
            [56,50,175, $tickets['program']],
            [48,100,175, $tickets['program']],
            [51,8,175, $tickets['program']],
            [58,6,175, $tickets['program']],
        ];

        collect($data)->each(function ($item) {
            Organization::find($item[0])->items()->create([
                'quantity' => $item[1],
                'cost' => $item[2] * 100,
                'item_id' => $item[3],
                'org_type' => 'ticket'
            ]);
        });

        $data = [
            [6,2,10],
            [7,3,10],
            [5,9,10],
            [3,10,10],
            [9,2,8],
            [12,3,8],
            [11,4,8],
            [8,5,8],
            [10,15,8],
            [13,4,11],
            [14,5,1],
            [15,7,7],
            [24,3,12],
            [25,4,12],
            [21,5,12],
            [19,6,12],
            [26,6,12],
            [18,11,12],
            [28,6,2],
            [31,9,2],
            [34,9,2],
            [33,12,2],
            [27,22,2],
            [32,28,2],
            [30,76,2],
            [1,120,2],
            [36,3,14],
            [38,3,14],
            [35,20,14],
            [39,5,16],
            [40,7,16],
            [43,3,17],
            [45,6,18],
            [44,11,18],
            [46,15,18],
            [66,5,9],
            [68,6,9],
            [70,7,9],
            [62,7,9],
            [69,7,9],
            [60,7,9],
            [61,11,9],
            [65,15,9],
            [59,19,9],
            [67,24,9],
            [64,25,9],
            [71,7,6],
        ];

        collect($data)->each(function ($item) {
            Organization::find($item[0])->items()->create([
                'quantity' => $item[1],
                'cost' => 0,
                'item_id' => $item[2],
                'org_type' => 'hotel'
            ]);
        });

        $data = [
            [68,1150,'check','Wire 10/27'],
            [20,5000,'stripe','ch_19AkZVAD1oCai5xuvSoVaNXP'],
            [53,1500,'stripe','ch_19hkxIAD1oCai5xuG5aLQbIa'],
            [37,1250,'stripe','ch_19bJgxAD1oCai5xuqcKM66qv'],
            [72,1600,'stripe','ch_18hKwBAD1oCai5xuqvBCXXug'],
            [17,2800,'stripe','ch_199KuyAD1oCai5xuE82pPgJD'],
            [57,350,'stripe','ch_19pi9QAD1oCai5xuShLnPHlK'],
            [23,1200,'stripe','ch_19YHo9AD1oCai5xun56Bq7hA'],
            [52,3500,'stripe','ch_19Ile6AD1oCai5xuru2saQ9M'],
            [29,1250,'stripe','ch_19fwxeAD1oCai5xu4lTU4rak'],
            [47,5000,'stripe','ch_18dh1GAD1oCai5xuBLsNfDqf'],
            [4,750,'stripe','ch_193sfPAD1oCai5xu18iLPxCs'],
            [12,700,'stripe','ch_19vTQNAD1oCai5xuFGjj6YHe'],
            [70,1800,'stripe','ch_19YMZIAD1oCai5xuuiOg92XN'],
            [19,750,'stripe','ch_199HxzAD1oCai5xuvhChMFHo'],
            [24,700,'stripe','ch_19cixPAD1oCai5xuDmoIPqzv'],
            [36,500,'stripe','ch_19NqifAD1oCai5xu1bLnXvdS'],
            [50,1000,'stripe','ch_1974DhAD1oCai5xu2fRPPLJN'],
            [7,600,'stripe','ch_19AlXdAD1oCai5xu0XUMmN26'],
            [71,1750,'stripe','ch_18iqCsAD1oCai5xumJhVNKtW'],
            [46,3500,'stripe','ch_198aIUAD1oCai5xuoGxFBqkL'],
            [49,3000,'stripe','ch_196iOSAD1oCai5xun4KOUK17'],
            [61,2250,'stripe','ch_19AhP3AD1oCai5xuHD0KeVw5'],
            [13,800,'stripe','ch_199DqdAD1oCai5xusnLrg5kf'],
            [28,1800,'stripe','ch_18Zlj2AD1oCai5xumeMAkpQR'],
            [18,2200,'stripe','ch_198ayuAD1oCai5xuODRrQFUu'],
            [14,1000,'stripe','ch_19irMhAD1oCai5xuKr4YsPsY'],
            [59,3750,'stripe','ch_19AleBAD1oCai5xuopxCXwAK'],
            [67,6250,'stripe','ch_18yobEAD1oCai5xu7hXCXnr1'],
            [62,1250,'stripe','ch_19fwxeAD1oCai5xu4lTU4rak'],
            [26,1250,'stripe','ch_19mVT8AD1oCai5xu1IzwCDzM'],
            [48,5000,'stripe','ch_193sfPAD1oCai5xu18iLPxCs'],
            [69,1350,'stripe','ch_19FmcNAD1oCai5xuANE39pRq'],
            [21,2000,'stripe','ch_19GsxMAD1oCai5xuUgWIv9MN'],
            [25,800,'stripe','ch_19kxv4AD1oCai5xuxiEiTwOg'],
            [38,550,'stripe','ch_19eVzeAD1oCai5xurNfQfrDV'],
            [44,3500,'stripe','ch_18ra2lAD1oCai5xuhKeW2k3k'],
            [40,1500,'stripe','ch_19pk9EAD1oCai5xuv7cGECHJ'],
            [8,1200,'stripe','ch_18bEYZAD1oCai5xupYPf8Abp'],
            [51,400,'stripe','ch_19EIe3AD1oCai5xuCHGsKtFO'],
            [43,650,'stripe','ch_19wtcNAD1oCai5xu9HLh0C6t'],
            [10,3250,'stripe','ch_19R6mSAD1oCai5xuu14SCiZp'],
            [58,300,'stripe','ch_19vzeKAD1oCai5xuTD7k8Qi0'],
            [65,2550,'stripe','ch_18gxhnAD1oCai5xuAnIvls6z'],
            [65,400,'stripe','ch_19xe0hAD1oCai5xuDuuoKwIm'],
            [9,450,'stripe','ch_19AhEbAD1oCai5xuHdq4xJTR'],
            [9,50,'stripe','ch_19NStuAD1oCai5xu5IMJuTZX'],
            [22,750,'check','Check 1/11'],
            [54,3000,'check','Check 1/20'],
            [3,2250,'check','Check 10/29'],
            [6,400,'check','Check 11/15'],
            [64,5000,'check','Check 11/15'],
            [35,4000,'check','Check 11/15'],
            [41,2500,'check','Check 11/2'],
            [5,2500,'check','Check 11/7'],
            [60,2000,'check','Check 11/7'],
            [15,2500,'check','Check 18663'],
            [55,3500,'check','Check 2/13'],
            [39,1000,'check','Check 2/13'],
            [56,2500,'check','Check 2/21'],
            [11,800,'check','Check 3/13'],
            [27,5500,'check','Check 8/26'],
            [31,1800,'check','Check 80828, $37000'],
            [32,5550,'check','Check 80828, $37000'],
            [33,2500,'check','Check 80828, $37000'],
            [30,25350,'check','Check 80828, $37000'],
            [34,1800,'check','Check 80828, $37000'],
            [63,2500,'check','Check 9/27'],
            [45,1500,'check','Check 9/27'],
            [66,1350,'check','Check 9/8'],
            [1,35000,'check','Wire'],
        ];

        collect($data)->each(function ($item) {
            Organization::find($item[0])->addTransaction([
                'source' => $item[2],
                'identifier' => $item[3],
                'amount' => $item[1] * 100,
            ]);
        });
    }
}
