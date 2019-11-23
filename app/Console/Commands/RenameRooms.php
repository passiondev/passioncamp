<?php

namespace App\Console\Commands;

use App\Organization;
use Illuminate\Console\Command;

class RenameRooms extends Command
{
    protected $signature = 'passioncamp:rename-rooms {organizationIds?*}';

    public function handle()
    {
        $orgIds = $this->argument('organizationIds');

        Organization::has('rooms')
            ->when(!empty($orgIds), function ($q) use ($orgIds) {
                $q->whereIn('id', $orgIds);
            })
            ->each(function ($organization) {
                $this->comment('org: '.$organization->id);
                $i = 1;
                $organization->rooms()->orderBy('id')->each(function ($room) use (&$i) {
                    $this->comment('room: '.$room->id);
                    $room->update([
                        'name' => 'Room #'.$i++,
                    ]);
                });
            });
    }
}
