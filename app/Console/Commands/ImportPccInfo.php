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
        $reader = Reader::createFromPath(storage_path('app/pcc.csv'));
        // Skip the header
        $all = $reader->setOffset(1)->fetchAll();

        collect($all)->mapWithKeys(function ($row) {
            return [
                $row[0] => [
                    'squad' => $row[1],
                    'leader' => $row[2],
                    'bus' => $row[3],
                ]
            ];
        })
        // ->dd()
        ->each(function ($row, $id) {
            if (! $id) return;

            try {
                $ticket = Ticket::findOrFail($id);
                $ticket->fill([
                    'ticket_data' => collect($ticket->ticket_data)->merge($row)
                ]);
            } catch (\Exception $e) {
                $this->line($id);
                $this->error($e->getMessage());
            }
        });
        $this->info('Done!');
    }
}
