@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Attendees</h1>
            <h2>{{ $organization->church->name }}</h2>
        </header>

        <table class="table">
            @foreach ($tickets as $ticket)
                <tr>
                    <th>{{ $ticket->person->name ?? '' }}</th>
                    <td>{{ $ticket->agegroup }}</td>
                    <td>{{ $ticket->person->grade ?? '' }}</td>
                    <td>@currency($ticket->price)</td>
                </tr>
            @endforeach
        </table>
    </div>
@stop
