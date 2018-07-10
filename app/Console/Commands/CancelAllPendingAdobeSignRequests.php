<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Contracts\EsignProvider;
use App\Jobs\Waiver\UpdateAgreementStatus;

class CancelAllPendingAdobeSignRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passioncamp:cancel-pending-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle(EsignProvider $provider)
    {
        $agreements = collect(json_decode(file_get_contents(storage_path('app/pendingrequests.json')))->userAgreementList);

        $this->info(
            $agreements->count()
        );

        collect($agreements)->each(function ($agreement) {
            UpdateAgreementStatus::dispatch($agreement, [
                'value' => 'CANCEL',
            ]);

            $this->info($agreement->agreementId);
        });
    }
}
