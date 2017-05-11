@extends('layouts.bootstrap4')

@section('content')
    <div class="container-fluid">
        <header class="d-lg-flex justify-content-between align-items-center mb-lg-2">
            <h1 class="mb-2 mb-lg-0">Waivers</h1>
        </header>

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
                            @unless ($ticket->waiver)
                                <send-waiver href="{{ action('TicketWaiversController@store', $ticket) }}" btn-style="outline-primary" v-cloak>
                                    Send Waiver
                                </send-waiver>
                            @else
                                {{ ucfirst($ticket->waiver->status) }}

                                @can('update', $ticket->waiver)
                                    @if (! $ticket->waiver->isComplete())
                                        <a href="{{ action('WaiversController@refresh', $ticket->waiver) }}" class="btn btn-sm btn-outline-info ml-2" onclick="event.preventDefault(); document.getElementById('refresh-{{ $ticket->waiver->id }}-form').submit()">Refresh</a>

                                        <form id="refresh-{{ $ticket->waiver->id }}-form" action="{{ action('WaiversController@refresh', $ticket->waiver) }}" method="POST" style="display:none">
                                            {{ csrf_field() }}
                                        </form>
                                    @endif
                                @endcan

                                @can('delete', $ticket->waiver)
                                    @if (! $ticket->waiver->isComplete())
                                        <a href="{{ action('WaiversController@destroy', $ticket->waiver) }}" class="btn btn-sm btn-outline-danger ml-2" onclick="event.preventDefault(); return (confirm('Are you sure you want to cancel this waiver?') ? document.getElementById('cancel-{{ $ticket->waiver->id }}-form').submit() : null)">Cancel</a>

                                        <form id="cancel-{{ $ticket->waiver->id }}-form" action="{{ action('WaiversController@destroy', $ticket->waiver) }}" method="POST" style="display:none">
                                            {{ method_field('DELETE') }}
                                            {{ csrf_field() }}
                                        </form>
                                    @endif
                                @endcan
                            @endif
                        </td>
                        <td>
                            {{ $ticket->waiver ? $ticket->waiver->updated_at->diffForHumans() : ''  }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
