<?php

namespace App\Http\Controllers;

use Auth;
use Gate;
use App\Order;
use App\Organization;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::search($request->search)->with('tickets', 'tickets.person');

        // if user is not super admin, add org id to query
        if (! Auth::user()->is_super_admin) {
            $orders->where('organization_id', Auth::user()->organization_id);
        }

        $orders = $orders->paginate(5);

        return view('order.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('owner', $order);

        $order->load('tickets', 'tickets.person', 'donations', 'items', 'transactions', 'transactions.transaction', 'organization');

        return view('order.show', compact('order'));
    }

    public function create()
    {
        $organizationOptions = [];
        Organization::with('church')->get()->sortBy('church.name')->each(function ($organization) use (&$organizationOptions) {
            $organizationOptions[$organization->id] = $organization->church->name . ', ' . $organization->church->city . ', ' . $organization->church->state;
        });

        return view('order.create', compact('organizationOptions'));
    }

    public function store(Request $request)
    {
        $organization = null;
        if (Auth::user()->is_super_admin) {
            $organization = Organization::findOrFail($request->organization);
        }

        if (! $organization) {
            $organization = Auth::user()->organization;
        }

        $order = new Order;
        $order->organization()->associate($organization);
        $order->addContact($request->only('first_name', 'last_name', 'email', 'phone'));
        $order->save();

        return redirect()->route('order.show', $order)->with('success', 'Regsirtation created.');
    }
}
