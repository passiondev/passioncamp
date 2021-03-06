<?php

namespace App\Console\Commands;

use App\Ticket;
use League\Csv\Reader;
use Illuminate\Console\Command;
use App\Repositories\TicketRepository;

class ImportPccInfo extends Command
{
    private $tickets;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pcc:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import PCC info';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $csv = Reader::createFromPath(storage_path('app/pcc.csv'));
        $csv->setHeaderOffset(0);

        collect($csv->getRecords())
        ->each(function ($row) {
            if (! $row['id']) {
                return;
            }

            try {
                $ticket = Ticket::findOrFail($row['id']);
                unset($row['id']);
                $ticket->update([
                    'ticket_data' => collect($ticket->ticket_data)->merge($row)
                ]);
            } catch (\Exception $e) {
                $this->line($row['id']);
                $this->error($e->getMessage());
            }
        });
        $this->info('Done!');
    }
}
