<?php

namespace App\Console\Commands;

use App\Ticket;
use App\Mail\WaiverRequest;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWaiverToTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passion:send-waiver {--before=} {--id=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
        $this->line(json_encode($this->options()));

        $tickets = Ticket::query()
            ->when($this->option('before'), function ($q) {
                $q->where('created_at', '<=', Carbon::parse($this->option('before')));
            })
            ->when($this->option('id'), function ($q) {
                $q->whereIn('id', $this->option('id'));
            })
            ->whereNull('deleted_at')
            ->with('order.user.person')
            ->get();

        $this->info('Tickets: '.$tickets->count());

        if (!$tickets->count()) {
            $this->line('Bye!');

            return;
        }

        $this->table(['Email', 'Name', 'Event'], $tickets->map(function ($ticket) {
            return [
                $ticket->order->user->person->email,
                $ticket->person->name,
                $ticket->order->organization->slug,
            ];
        }));

        if (!$this->confirm('Proceed?')) {
            $this->line('Bye!');

            return;
        }

        $bar = $this->output->createProgressBar($tickets->count());

        $bar->start();

        foreach ($tickets as $ticket) {
            Mail::to($ticket->order->user->person->email ?? 'matt.floyd@268generation.com')
                ->queue(new WaiverRequest($ticket));

            sleep(1);

            $bar->advance();
        }

        $bar->finish();
    }
}
