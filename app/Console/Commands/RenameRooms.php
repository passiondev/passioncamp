<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Organization;

class RenameRooms extends Command
{
    protected $signature = 'passioncamp:rename-rooms';

    public function handle()
    {
        Organization::has('rooms')->each(function ($organization) {
            $this->comment('org: ' . $organization->id);
            $i = 1;
            $organization->rooms()->orderBy('id')->each(function ($room) use (&$i) {
                $this->comment('room: ' . $room->id);
                $room->update([
                    'name' => 'Room #' . $i++
                ]);
            });
        });
    }
}
