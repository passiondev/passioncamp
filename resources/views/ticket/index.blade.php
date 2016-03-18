@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Attendees</h1>
        </header>

        <table class="table">
            @foreach ($tickets as $ticket)
                <tr>
                    <th>{{ $ticket->person->name }}</th>
                    @if (Auth::user()->is_super_admin)
                        <td>{{ $ticket->organization->church->name }}</td>
                        <td>{{ $ticket->organization->church->location }}</td>
                    @endif
                    <td>{{ $ticket->agegroup }}</td>
                    <td>{{ number_ordinal($ticket->person->grade) }}</td>
                    <td>@currency($ticket->price)</td>
                </tr>
            @endforeach
        </table>

        {{ $tickets->appends(Request::only('search'))->links() }}
    </div>
@stop
