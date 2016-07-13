@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        @if (session('uncheck'))
            <div class="ui yellow message" style="display:flex;justify-content:space-between;align-items:center">
                {{ session('ticket_name') }} has been un-checked in.
            </div>
        @endif
        @if (session('ticket_id'))
            <div class="ui success message" style="display:flex;justify-content:space-between;align-items:center">
                {{ session('ticket_name') }} has been checked in.
                <form action="/checkin/{{ session('ticket_id') }}/undo" method="POST">
                    {{ csrf_field() }}
                    <button type="submit" class="ui green basic button">Undo?</button>
                </form>
            </div>
        @endif

        <h2 class="ui top attached header">Check In</h2>

        <div class="ui attached segment">
            <form action="/checkin" method="GET" class="ui form">
                <div class="ui big fluid action input">
                    <input autofocus type="search" name="search" class="form-control input-group-field" placeholder="Search..." value="{{ request('search') }}">
                    <button class="ui icon button" type="submit"><i class="search icon"></i></button>
                </div>
            </form>
        </div>

        <table class="ui attached striped table">
            @foreach ($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->person->name }}</td>
                    <td>
                        <form action="/checkin/{{ $ticket->id }}" method="POST">
                            {{ csrf_field() }}
                            <button type="submit" class="ui primary button">Check In</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@stop
