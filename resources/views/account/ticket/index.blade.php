@extends('layouts.bootstrap4')

@section('content')
    <div class="ui container">
        <header class="d-flex justify-content-between">
            <h1>Attendees</h1>
            <p>
                @unless (auth()->user()->isSuperAdmin())
                    <a href="{{ action('Account\TicketController@create') }}" class="btn btn-primary">Add Attendee</a>
                @endunless
            </p>
        </header>

        @unless($tickets->count())
            <p class="py-5">
                <i>No attendees added yet...</i>
            </p>
        @else
            <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Grade</th>
                    <th></th>
                </tr>
            </thead>
                @foreach ($tickets as $ticket)
                    <tr class="{{ $ticket->is_canceled ? 'canceled' : '' }}">
                        <td>
                            {{ $ticket->name }}
                        </td>
                        <td>
                            @include('ticket/partials/label')
                        </td>
                        <td>
                            <a href="{{ action('TicketController@edit', $ticket) }}">edit</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif

        {{ $tickets->links() }}
    </div>
@stop
