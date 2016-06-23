<?php

namespace App\Http\Controllers\Organization;

use App\Item;
use App\OrderItem;
use App\Organization;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\OrderItems\OrgItemUpdated;

class ItemController extends Controller
{
    public function create(Organization $organization)
    {
        $items = $this->getItemOptions();

        return view('admin.organization.item.create', compact('items'))->withOrganization($organization);
    }

    public function store(Request $request, Organization $organization)
    {
        $item = Item::find($request->item);

        $orderItem = new OrderItem;
        $orderItem->item()->associate($item);
        $orderItem->organization()->associate($organization);
        $orderItem->fill($request->only('cost', 'quantity'));
        $orderItem->org_type = $item->type;
        $orderItem->save();

        app()->call([new App\Jobs\DeployRoomsAndAssignToHotels, 'handle']);

        return redirect()->route('admin.organization.show', $organization)->with('success', 'Item added.');
    }

    public function edit(Organization $organization, OrderItem $item)
    {
        $items = $this->getItemOptions();

        return view('admin.organization.item.edit', compact('items', 'item'))->withOrganization($organization);
    }

    public function update(Request $request, Organization $organization, OrderItem $item)
    {
        $item->fill($request->only('item_id', 'cost', 'quantity'));
        $item->save();

        app()->call([new App\Jobs\DeployRoomsAndAssignToHotels, 'handle']);
        
        return redirect()->route('admin.organization.show', $organization)->with('success', 'Item updated.');
    }

    private function getItemOptions()
    {
        $items = [];

        Item::all()->each(function ($item) use (&$items) {
            $items[$item->id] = $item->name;
        });

        return $items;
    }
}
