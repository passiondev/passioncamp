@extends('layouts.bootstrap4')

@section('content')
    <div class="container-fluid">
        <header class="d-lg-flex justify-content-between align-items-center mb-lg-2">
            <h1 class="mb-2 mb-lg-0">Waivers</h1>
        </header>

        @if (auth()->user()->isSuperAdmin())
            <form action="{{ action('WaiversController@index') }}" method="GET" class="form-inline mb-3">
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
                                    <div>
                                        @icon('checkmark', 'text-success', ['v-if' => 'updated'])
                                        <span v-text="waiver.status" class="text-capitalize"></span>
                                        <div v-if="! updated">
                                            @can('update', $ticket->waiver)
                                                <ajax href="{{ action('WaiversController@refresh', $ticket->waiver) }}" method="POST" class="btn btn-sm btn-outline-info" @success="waiver = {status: 'Refreshing'};updated = true;" v-cloak>
                                                    Refresh
                                                </ajax>
                                            @endcan

                                            @can('delete', $ticket->waiver)
                                                <ajax href="{{ action('WaiversController@destroy', $ticket->waiver) }}" method="DELETE" class="btn btn-sm btn-outline-danger ml-2" confirm="Are you sure you want to cancel this waiver?" @success="waiver = {status: 'Canceled'};updated = true;" v-cloak>
                                                    Cancel
                                                </ajax>
                                            @endcan

                                            @if (auth()->user()->isSuperAdmin() && ! $ticket->waiver->isComplete())
                                                <ajax href="{{ action('TicketWaiversController@store', ['ticket' => $ticket, 'completed' => 1]) }}" method="POST" class="btn btn-sm btn-outline-secondary ml-2" @success="waiver = {status: 'Complete'};updated = true;" v-cloak>
                                                    Complete
                                                </ajax>
                                            @endif
                                        </div>
                                    </div>
                                </ticket-waiver>
                            @else
                                <ticket-waiver :data="{{ json_encode($ticket->waiver) }}" inline-template v-cloak>
                                    <div v-if="!! waiver" class="text-capitalize">
                                        @icon('checkmark', 'text-success', ['v-if' => 'updated'])

                                        @{{ status }}
                                    </div>

                                    <div v-else>
                                        <ajax href="{{ action('TicketWaiversController@store', $ticket) }}" method="POST" class="btn btn-sm btn-outline-primary" @success="success">
                                            Send Waiver
                                        </ajax>

                                        @if (auth()->user()->isSuperAdmin())
                                            <ajax href="{{ action('TicketWaiversController@store', ['ticket' => $ticket, 'completed' => 1]) }}" method="POST" class="btn btn-sm btn-outline-secondary ml-2" @success="success">
                                                Complete
                                            </ajax>
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
