<?php

namespace App\Jobs;

use App\Room;
use App\Jobs\Job;
use App\OrderItem;
use App\Organization;
use App\Repositories\RoomRepository;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeployRoomsAndAssignToHotels extends Job implements ShouldQueue
{
    private $rooms;

    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(RoomRepository $rooms = null)
    {
        $this->rooms = $rooms ?? app()->make(RoomRepository::class);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rooms = Room::all();
        $organizations = Organization::has('hotelItems')->with('hotelItems', 'rooms')->get();

        \DB::beginTransaction();

        $organizations->each(function ($organization) {
            $hotels = $organization->hotelItems->active()->groupBy('item_id')
                ->map(function ($hotel) use ($organization) {
                    return [
                        'hotel_id' => $hotel->first()->item_id,
                        'qty' => $hotel->sum('quantity')
                    ];
                })->reject(function ($hotel) {
                    return $hotel['qty'] == 0;
                });

            $organization->rooms->filter(function ($room) {
                return $room->hotel_id;
            })->each(function ($room) use ($hotels) {
                $hotel = $hotels->first(function ($key) use ($room) {
                    return $key == $room->hotel_id;
                }, ['hotel_id' => null, 'qty' => 0]);

                $hotels->forget($hotel['hotel_id']);
                $hotel['qty']--;
                $hotels->offsetSet($hotel['hotel_id'], $hotel);             
            });

            $organization->rooms->reject(function ($room) {
                return $room->hotel_id;
            })->each(function ($room) use ($hotels) {
                $hotel = $hotels->first(function ($key, $hotel) {
                    return $hotel['qty'] > 0;
                }, ['hotel_id' => null, 'qty' => 0]);
                
                if ($hotel['qty'] <= 0) return false;

                $hotels->forget($hotel['hotel_id']);

                $room->hotel_id = $hotel['hotel_id'];
                $room->save();

                $hotel['qty']--;
                $hotels->offsetSet($hotel['hotel_id'], $hotel);
            });

            $hotels->filter(function ($hotel) {
                return $hotel['qty'] > 0;
            })->each(function ($hotel) use ($organization) {
                $total_rooms = $organization->rooms()->withTrashed()->count();
                for ($i = 1; $i <= $hotel['qty']; $i++) {
                    $this->rooms->create($organization, 'Room #' . ($total_rooms+$i), $hotel['hotel_id']);
                }
            });
        });

        \DB::commit();
    }
}
