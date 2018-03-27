<?php

namespace App\Http\Controllers;

use App\Item;
use App\OrgItem;
use App\Organization;

class OrganizationItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('super');

        $this->authorizeResource(OrgItem::class, 'item');
    }

    public function create(Organization $organization)
    {
        $items = Item::orderBy('type')->orderBy('name')->get()->groupBy('type');

        return view('admin.organization.item.create', compact(['organization', 'items']));
    }

    public function store(Organization $organization)
    {
        $item = Item::find(request('item'));

        $organization->items()->make([
            'quantity' => request()->input('quantity'),
            'cost' => request()->input('cost') * 100,
            'notes' => request()->input('notes'),
            'org_type' => $item->type,
        ])->item()->associate($item)->save();

        return redirect()->route('admin.organizations.show', $organization)->withSuccess('Item added.');
    }

    public function edit(Organization $organization, OrgItem $item)
    {
        return view('admin.organization.item.edit', compact('item', 'organization'));
    }

    public function update(Organization $organization, OrgItem $item)
    {
        $item->update([
            'notes' => request('notes'),
            'quantity' => request('quantity'),
            'cost' => request('cost') * 100
        ]);

        return redirect()->route('admin.organizations.show', $organization)->withSuccess('Item updated.');
    }

    public function destroy(Organization $organization, OrgItem $item)
    {
        $item->delete();

        return redirect()->route('admin.organizations.show', $organization);
    }
}
