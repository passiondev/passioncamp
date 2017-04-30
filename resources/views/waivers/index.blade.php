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
                                <send-waiver href="{{ action('TicketWaiversController@store', $ticket) }}" btn-style="outline-primary">
                                    Send Waiver
                                </send-waiver>

                                {{-- <form onsubmit="event.preventDefault(); alert('submit');" action="{{ action('TicketWaiversController@store', $ticket) }}" method="POST" id="waiver-{{ $ticket->id }}-form" style="display:none">
                                    {{ csrf_field() }}
                                </form> --}}
                            @elseif ($ticket->waiver->canBeReminded())
                                <send-waiver href="{{ action('WaiversController@reminder', $ticket->waiver) }}" btn-style="outline-secondary">
                                    Remind
                                </send-waiver>
                            @else
                                {{ ucfirst($ticket->waiver->status) }}
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
