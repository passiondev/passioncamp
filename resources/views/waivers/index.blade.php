@extends('layouts.bootstrap4')

@section('content')
    <div class="container-fluid">
        <header class="d-lg-flex justify-content-between align-items-center mb-lg-2">
            <h1 class="mb-2 mb-lg-0">Waivers</h1>
        </header>

        @if (auth()->user()->isSuperAdmin())
            <form action="{{ action('WaiverController@index') }}" method="GET" class="form-inline mb-3">
                <select name="organization" class="form-control mb-2 mr-sm-2 mb-sm-0" onchange="this.form.submit()">
                    <option selected disabled>Church...</option>
                    <option value="">- All -</option>
                    @foreach ($organizations as $organization)
                        <option value="{{ $organization->id }}" @if (request('organization') == $organization->id) selected @endif>
                            {{ $organization->church->name }} - {{ $organization->church->location }}
                        </option>
                    @endforeach
                </select>
            </form>
        @endif

        <table class="table table-responsive table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    @if (auth()->user()->isSuperAdmin())
                        <th></th>
                    @endif
                    <th>Contact</th>
                    <th>Status</th>
                    <th>Last Updated</th>
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
                            @if ($ticket->waiver && $ticket->waiver->isComplete())
                                {{ ucfirst($ticket->waiver->status) }}
                            @elseif ($ticket->waiver)
                                    <ticket-waiver :data="{{ json_encode($ticket->waiver) }}" inline-template v-cloak>
                                        <ul class="list-unstyled mb-0" style="font-size:85%">

                                            <li v-show="status == 'canceled' && updated" class="mb-2">
                                                <ajax href="{{ route('tickets.waivers.store', $ticket) }}" method="POST" class="btn btn-sm btn-outline-primary" @success="success">
                                                    Send Waiver
                                                </ajax>
                                            </li>

                                            <li class="text-capitalize">
                                                @icon('checkmark', 'text-success', ['v-if' => 'updated'])
                                                <em v-text="waiver.status" class="text-capitalize"></em>
                                            </li>

                                            <li v-if="! updated">
                                                <ajax href="{{ action('WaiverController@refresh', $ticket->waiver) }}" method="POST" @success="waiver = {status: 'refreshing'};updated = true;" v-cloak>
                                                    refresh
                                                </ajax>
                                            </li>

                                            <li v-if="! updated">
                                                <ajax href="{{ action('WaiverController@destroy', $ticket->waiver) }}" method="DELETE" class="text-danger" confirm="Are you sure you want to cancel this waiver?" @success="waiver = {status: 'canceled'};updated = true;" v-cloak>
                                                    cancel
                                                </ajax>
                                            </li>

                                            @if (auth()->user()->isSuperAdmin() && ! $ticket->waiver->isComplete())
                                                <li v-if="! updated">
                                                    <ajax href="{{ route('tickets.waivers.store', ['ticket' => $ticket, 'completed' => 1]) }}" method="POST" class="text-muted" confirm="Are you sure you want to mark this waiver completed?" @success="waiver = {status: 'Complete'};updated = true;" v-cloak>
                                                        complete
                                                    </ajax>
                                                </li>
                                            @endif
                                        </ul>
                                    </ticket-waiver>
                            @else
                                <ticket-waiver :data="{{ json_encode($ticket->waiver) }}" inline-template v-cloak>
                                    <div v-if="!! waiver" class="text-capitalize">
                                        @icon('checkmark', 'text-success', ['v-if' => 'updated'])

                                        @{{ status }}
                                    </div>

                                    <ul class="list-unstyled mb-0" style="font-size:85%" v-else>
                                        <li class="mb-1">
                                            <ajax href="{{ route('tickets.waivers.store', $ticket) }}" method="POST" class="btn btn-sm btn-outline-primary" @success="success">
                                                Send Waiver
                                            </ajax>
                                        </li>

                                        @if (auth()->user()->isSuperAdmin())
                                            <li>
                                                <ajax href="{{ route('tickets.waivers.store', ['ticket' => $ticket, 'completed' => 1]) }}" method="POST" confirm="Are you sure you want to mark this waiver completed?" class="text-muted" @success="success">
                                                    complete
                                                </ajax>
                                            </li>
                                        @endif
                                    </div>
                                </ticket-waiver>
                            @endif
                        </td>
                        <td>
                            {{ $ticket->waiver ? $ticket->waiver->updated_at->diffForHumans() : ''  }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $tickets instanceof Illuminate\Contracts\Pagination\LengthAwarePaginator ? $tickets->links() : '' }}
    </div>
@stop
