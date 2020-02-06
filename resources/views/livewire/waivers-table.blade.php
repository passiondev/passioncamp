<div>
@if (auth()->user()->isSuperAdmin())
    <div class="d-flex align-items-baseline mb-3">
        <select wire:model="organization" name="organization" class="form-control mb-2 mr-sm-2 mb-sm-0" onchange="this.form.submit()">
            <option selected disabled>Church...</option>
            <option value="">- All -</option>
            @foreach ($organizations as $organization)
                <option value="{{ $organization->id }}" @if (request('organization') == $organization->id) selected @endif>
                    {{ $organization->church->name }} - {{ $organization->church->location }}
                </option>
            @endforeach
        </select>

        @includeWhen(request()->input('organization'), 'waivers.partials.bulk-actions')
    </div>
@else
    <div class="d-flex align-items-baseline mb-3">
        @include('waivers.partials.bulk-actions')
    </div>
@endif

<table class="mb-3">
    <tr>
        <th class="pr-3">Total</th>
        <td>{{ $stats['total'] }}</td>
    </tr>
    <tr>
        <th class="pr-3">Complete</th>
        <td>{{ $stats['completed'] }}</td>
    </tr>
    <tr>
        <th class="pr-3">Pending</th>
        <td>{{ $stats['pending'] }}</td>
    </tr>
    <tr>
        <th class="pr-3">Not Sent</th>
        <td>{{ $stats['unsent'] }}</td>
    </tr>
</table>

<table class="table table-responsive table-striped">
    <thead>
        <tr>
            <th>
                <a wire:click.prevent="sortBy('name')" role="button" href="#">
                    Name
                    @if ($sortField === 'status')
                        <i class="text-muted fas fa-sort"></i>
                    @elseif ($sortAsc)
                        <i class="fas fa-sort-up"></i>
                    @else
                        <i class="fas fa-sort-down"></i>
                    @endif
                </a>
            </th>
            @if (auth()->user()->isSuperAdmin())
                <th></th>
            @endif
            <th>Contact</th>
            <th>
                <a wire:click.prevent="sortBy('status')" role="button" href="#">
                    Status
                    @if ($sortField === 'status')
                        <i class="text-muted fas fa-sort"></i>
                    @elseif ($sortAsc)
                        <i class="fas fa-sort-up"></i>
                    @else
                        <i class="fas fa-sort-down"></i>
                    @endif
                </a>
            </th>
            <th>
                <a wire:click.prevent="sortBy('updated_at')" role="button" href="#">
                    Last Updated
                    @if ($sortField === 'status')
                        <i class="text-muted fas fa-sort"></i>
                    @elseif ($sortAsc)
                        <i class="fas fa-sort-up"></i>
                    @else
                        <i class="fas fa-sort-down"></i>
                    @endif
                </a>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tickets as $ticket)
            <tr class="{{ $ticket->is_canceled ? 'canceled' : '' }}">
                <td>
                    @if ($ticket->order->organization->slug == 'pcc')
                        <a href="{{ action('OrderController@show', $ticket->order) }}">{{ $ticket->name }}</a>
                    @else
                        <a href="{{ action('TicketController@edit', $ticket) }}">{{ $ticket->name }}</a>
                    @endif
                </td>
                @if (auth()->user()->isSuperAdmin())
                    <td>{{ $ticket->order->organization->church->name }}<br> <small>{{ $ticket->order->organization->church->location }}</small></td>
                @endif
                <td>
                    {{ $ticket->order->user->person->email }}
                </td>
                <td>
                    @if (optional($ticket->latestWaiver)->isComplete())
                        {{ ucfirst($ticket->latestWaiver->status) }}
                    @else
                        @livewire('waiver-actions', $ticket, key($ticket->id))
                    @endif
                </td>
                <td>
                    {{ $ticket->latestWaiver ? $ticket->latestWaiver->updated_at->diffForHumans() : ''  }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $tickets->links() }}
Showing {{ $tickets->firstItem() }} to {{ $tickets->lastItem() }} of {{ $tickets->total() }}
</div>
