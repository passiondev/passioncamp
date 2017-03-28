@extends('layouts.bootstrap4')

@section('content')
    <div class="ui container">
        <header class="d-flex justify-content-between">
            <h1>Attendees</h1>
            <div>
                @unless (auth()->user()->isSuperAdmin() || auth()->user()->organization->tickets_remaining_count <= 0)
                    <a href="{{ action('Account\TicketController@create') }}" class="btn btn-secondary">Add Attendee</a>
                @endunless
                <a href="{{ action('TicketExportController@store') }}" class="btn btn-secondary" onclick="event.preventDefault(); document.getElementById('export-form').submit();">Export...</a>
            </div>
        </header>

        @unless($tickets->count())
            <p class="py-5">
                <i>No attendees added yet...</i>
            </p>
        @else
            <table class="table table-responsive table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        @if (auth()->user()->isSuperAdmin())
                            <th></th>
                        @endif
                        <th>Grade</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tickets as $ticket)
                        <tr class="{{ $ticket->is_canceled ? 'canceled' : '' }}">
                            <td>
                                @if ($ticket->order->organization->slug == 'pcc')
                                    <a href="{{ action('OrderController@show', $ticket->order) }}">{{ $ticket->name }}</a>
                                @else
                                    {{ $ticket->name }}
                                @endif
                            </td>
                            @if (auth()->user()->isSuperAdmin())
                                <td>{{ $ticket->order->organization->church->name }}<br> <small>{{ $ticket->order->organization->church->location }}</small></td>
                            @endif
                            <td>
                                @include('ticket/partials/label')
                            </td>
                            <td>
                                <a href="{{ action('TicketController@edit', $ticket) }}" class="btn btn-outline-secondary btn-sm">edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- {{ $tickets->appends(Request::only('search'))->links(new \App\Pagination\Semantic($tickets)) }} --}}
        {{ $tickets->links() }}
    </div>

    <form action="{{ action('TicketExportController@store') }}" method="POST" id="export-form">
        {{ csrf_field() }}
    </form
@stop
