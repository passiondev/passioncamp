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
    protected $signature = 'import:pcc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import PCC info';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TicketRepository $tickets)
    {
        parent::__construct();
        $this->tickets = $tickets;
    }

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

        $ids = collect($all)->pluck('0');

        $data = collect($all)->map(function ($row) {
            return [
                'id' => $row[0],
                'pcc_waiver' => $row[1],
                'description' => $row[2],
                'leader' => $row[3],
                'squad' => $row[4],
                'bus' => $row[5],
            ];
        })->keyBy('id');

        $tickets = Ticket::whereIn('id', $ids)->with('room')->get()->keyBy('id');

        $tickets->each(function ($ticket, $i) use ($data) {
            $room = $ticket->room;
            $room->description = $data[$i]['description'];
            $room->save();

            $this->tickets->update($ticket, $data[$i]);
        });
    }
}
