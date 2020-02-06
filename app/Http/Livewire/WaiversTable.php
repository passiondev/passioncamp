<?php

namespace App\Http\Livewire;

use App\Ticket;
use App\Organization;
use App\WaiverStatus;
use Livewire\Component;
use Livewire\WithPagination;

class WaiversTable extends Component
{
    use WithPagination;

    public $perPage = 20;

    public $organization;

    public $sortField = 'status';

    public $sortAsc = true;

    public function updating($name, $value)
    {
        if ($name === 'paginator') {
            return;
        }

        $this->gotoPage(1);
    }

    public function sortBy($field)
    {
        $this->sortAsc = $this->sortField === $field
            ? ! $this->sortAsc
            : true;

        $this->sortField = $field;
    }

    public function render()
    {
        $tickets = Ticket::forUser(auth()->user())
            ->active()
            ->when(auth()->user()->isSuperAdmin() && $this->organization, function ($q) {
                $q->select('order_items.*')
                    ->join('orders', function ($q) {
                        $q->whereRaw('owner_id = orders.id AND owner_type = "App\\\Order"');
                    })
                    ->where('orders.organization_id', $this->organization);
            })
            ->when($this->sortField, function ($q) {
                if ($this->sortField === 'name') {
                    return $q->orderByPersonName($this->sortAsc);
                }

                if ($this->sortField === 'status') {
                    return $q->orderByStatus($this->sortAsc);
                }

                return $q->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
            });

        $completedWaiversCount = (clone $tickets)
            ->whereHas('waiver', function ($q) {
                $q->complete()
                    ->whereRaw('order_items.id = ticket_id')
                    ->latest()
                    ->limit(1);
            })
            ->count();

        $pendingWaiversCount = (clone $tickets)
            ->whereHas('waiver', function ($q) {
                $q->where('status', WaiverStatus::PENDING)
                    ->whereRaw('order_items.id = ticket_id')
                    ->latest()
                    ->limit(1);
            })
            ->count();

        $paginated = $tickets
            ->resolveLatestWaiver()
            ->with('person', 'order.organization.church', 'order.user.person', 'latestWaiver')
            ->paginate($this->perPage);

        return view('livewire.waivers-table', [
            'tickets' => $paginated,
            'organizations' => Organization::orderByChurchName()->with('church')->get(),
            'stats' => array_merge(
                $stats = [
                    'total' => $paginated->total(),
                    'completed' => $completedWaiversCount,
                    'pending' => $pendingWaiversCount,
                ], [
                    'unsent' => $stats['total'] - $stats['completed'] - $stats['pending'],
                ]
            ),
        ]);
    }
}
