<?php

namespace App\Http\Controllers;

use App\Item;
use App\OrderItem;
use App\Organization;
use App\Jobs\DeployRoomsAndAssignToHotels;

class OrganizationItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('super');
    }

    public function create(Organization $organization)
    {
        $items = Item::orderBy('type')->orderBy('name')->get()->groupBy('type')->reverse();

        return view('admin.organization.item.create', compact(['organization', 'items']));
    }

    public function store(Organization $organization)
    {
        $item = Item::find(request('item'));

        $orderItem = new OrderItem(request(['quantity']) + [
            'cost' => request('cost') * 100
        ]);
        $orderItem->item()->associate($item);
        $orderItem->organization()->associate($organization);
        $orderItem->org_type = $item->type;
        $orderItem->save();

        // app()->call([new DeployRoomsAndAssignToHotels, 'handle']);

        return redirect()->action('OrganizationController@show', $organization)->withSuccess('Item added.');
    }

    public function edit(Organization $organization, OrderItem $item)
    {
        $items = Item::all();

        return view('admin.organization.item.edit', compact('items', 'item'))->withOrganization($organization);
    }

    public function update(Organization $organization, OrderItem $item)
    {
        $item->update(request('item_id', 'quantity') + [
            'cost' => request('cost') * 100
        ]);

        // app()->call([new DeployRoomsAndAssignToHotels, 'handle']);

        return redirect()->action('OrganizationController@show', $organization)->withSuccess('Item updated.');
    }
}
