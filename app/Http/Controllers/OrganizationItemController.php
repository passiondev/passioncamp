<?php

namespace App\Http\Controllers;

use App\Item;
use App\OrderItem;
use App\Organization;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\OrderItems\OrgItemUpdated;
use App\Jobs\DeployRoomsAndAssignToHotels;

class OrganizationItemController extends Controller
{
    public function create(Organization $organization)
    {
        $items = Item::all();

        return view('admin.organization.item.create', compact(['organization', 'items']));
    }

    public function store(Organization $organization)
    {
        $item = Item::find(request('item'));

        $orderItem = new OrderItem(request(['cost', 'quantity']));
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
        $item->update(request('item_id', 'cost', 'quantity'));

        // app()->call([new DeployRoomsAndAssignToHotels, 'handle']);

        return redirect()->action('OrganizationController@show', $organization)->withSuccess('Item updated.');
    }
}
