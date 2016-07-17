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
        // /* Each entry is shaped like this:
        //  *
        //  * [
        //  *     0 => "x",
        //  *     1 => "Jill Schmidt" // name
        //  *     2 => "jill@schmidt.com" // email
        //  *     3 => "4" // experience level w/laravel 1-5
        //  *     4 => "Stuff" // things I'd like to learn
        //  * ]
        //  */
        // // Filter and grab 50 randomly
        // $winners = collect($all)->filter(function ($person) {
        //     // Manually added an 'x' next to anyone whose story/reason was compelling;
        //     // turns out I "x'ed" 84 people, which is more than 50, so we're just gonna
        //     // use only them 
        //     return $person[0] == 'x';
        // })->random(50);

        $ids = collect($all)->pluck('0');

        $data = collect($all)->map(function ($row) {
            return [
                'pcc_waiver' => $row[1],
                'description' => $row[2],
                'leader' => $row[3],
                'squad' => $row[4],
                'bus' => $row[5],
            ];
        });

        $tickets = Ticket::whereIn('id', $ids)->with('room')->get();

        $tickets->each(function ($ticket, $i) use ($data) {
            $room = $ticket->room;
            $room->description = $data[$i]['description'];
            $room->save();

            $this->tickets->update($ticket, $data[$i]);
        });
    }
}
