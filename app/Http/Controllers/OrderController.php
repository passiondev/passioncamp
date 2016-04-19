<?php

namespace App\Http\Controllers;

use Auth;
use Gate;
use App\Order;
use App\Organization;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use App\Repositories\OrganizationRepository;

class OrderController extends Controller
{
    protected $orders;
    protected $organizations;

    public function __construct(OrderRepository $orders, OrganizationRepository $organizations)
    {
        $this->orders = $orders;
        $this->organizations = $organizations;
    }

    public function index(Request $request)
    {
        $orders = $this->orders
                  ->forUser(Auth::user())
                  ->search($request->search)
                  ->with('tickets.person')
                  ->paginate(5);

        return view('order.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('owner', $order);

        return view('order.show', compact('order'));
    }

    public function create()
    {
        $organizationOptions = $this->organizations->getChurchNameAndLocationList();

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
