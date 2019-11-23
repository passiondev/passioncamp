<?php

namespace App\Jobs\Organization;

use App\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeployRooms implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    private $organization;

    /**
     * Create a new job instance.
     */
    public function __construct(Organization $organization)
    {
        $this->organization = $organization;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        list($items, $rooms) = $this->mapItemsAndRooms();

        $this->addRooms($items, $rooms);
        $this->removeRooms($items, $rooms);
    }

    private function mapItemsAndRooms()
    {
        $items = $this->organization->hotelItems()->active()->get()->groupBy('item_id')->mapWithKeys(function ($items, $key) {
            return [$key => $items->sum('quantity')];
        });

        $rooms = $this->organization->rooms()->get()->groupBy('hotel_id')->mapWithKeys(function ($rooms, $key) {
            return [$key => \count($rooms)];
        });

        return [$items, $rooms];
    }

    private function addRooms($items, $rooms)
    {
        $items->mapWithKeys(function ($item, $key) use ($rooms) {
            $numRooms = $rooms[$key] ?? 0;

            if ($numRooms >= $item) {
                return [];
            }

            return [$key => $item - $numRooms];
        })->each(function ($count, $hotel_id) {
            $roomNumber = $this->organization->rooms()->count();

            $rooms = Collection::times($count, function ($i) use ($hotel_id, $roomNumber) {
                return $this->organization->rooms()->create([
                    'hotel_id' => $hotel_id,
                    'name' => sprintf('Room #%s', $roomNumber + $i),
                ]);
            });
        });
    }

    private function removeRooms($items, $rooms)
    {
        $rooms->mapWithKeys(function ($room, $key) use ($items) {
            $numItems = $items[$key] ?? 0;

            if ($numItems >= $room) {
                return [];
            }

            return [$key => $room - $numItems];
        })->each(function ($count, $hotel_id) {
            Collection::times($count, function () use ($hotel_id) {
                $this->organization->rooms()->where('hotel_id', $hotel_id)->get()->last()->delete();
            });
        });
    }
}
