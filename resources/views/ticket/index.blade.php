@extends('layouts.bootstrap4')

@section('content')
    <div class="container-fluid">
        <header class="d-lg-flex justify-content-between align-items-center mb-lg-2">
            <h1 class="mb-2 mb-lg-0">Attendees</h1>
            <form class="mb-2 mb-lg-0 ml-lg-4" action="{{ action('TicketController@search') }}" method="GET">
                <div class="input-group">
                    <input type="search" name="query" class="form-control input-go" placeholder="Search..." value="{{ request('query') }}">
                    <span class="input-group-btn">
                        <button class="btn btn-secondary">@icon('search')</button>
                    </span>
                </div>
            </form>
            <div class="mb-2 mb-lg-0 ml-auto">
                @unless (auth()->user()->isSuperAdmin() || auth()->user()->organization->tickets_remaining_count <= 0)
                    <a href="{{ action('Account\TicketController@create') }}" class="btn btn-secondary">Add Attendee</a>
                @endunless
                <a href="{{ action('TicketExportController@store') }}" class="btn btn-secondary" onclick="event.preventDefault(); document.getElementById('export-form').submit();">Export</a>
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

        {{ $tickets->links() }}
    </div>

    <form action="{{ action('TicketExportController@store') }}" method="POST" id="export-form">
        {{ csrf_field() }}
    </form>
@stop
