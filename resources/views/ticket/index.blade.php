@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1 class="page-header__title">Attendees</h1>
            <div class="page-header__actions">
                <a href="{{ route('ticket.export.index') }}">Export</a>
            </div>
        </header>

        <div class="callout primary">
            <form action="/tickets" method="GET">
                <div class="form-group input-group">
                    <input type="search" name="search" class="form-control input-group-field" placeholder="Search..." value="{{ request('search') }}">
                    <div class="input-group-button">
                        <button type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>

        @unless($tickets->count())
            <p><i>No results</i></p>
        @endif
        <table class="table">
            @foreach ($tickets as $ticket)
                <tr class="{{ $ticket->is_canceled ? 'canceled' : '' }}">
                    <th><a href="{{ route('order.show', $ticket->order) }}">{{ $ticket->name }}</a></th>
                    @if (Auth::user()->is_super_admin)
                        <td>{{ $ticket->organization->church->name }}</td>
                        <td>{{ $ticket->organization->church->location }}</td>
                    @endif
                    <td>
                        @include('ticket/partials/label')
                    </td>
                    @can ('record-transactions', $ticket->organization)
                        <td>@currency($ticket->price)</td>
                    @endcan
                    <td>
                        @can ('edit', $ticket)
                            @unless ($ticket->waiver)
                                <Waiver inline-template>
                                    <a v-on:click.prevent="send" href="{{ route('ticket.waiver.create', $ticket) }}">send waiver</a>
                                </Waiver>
                            @else
                                {{ $ticket->waiver->status }}<br>
                                @unless ($ticket->waiver->status == 'signed')
                                    <Waiver inline-template>
                                        <a href="{{ route('ticket.waiver.reminder', $ticket) }}">send reminder</a>
                                    </Waiver>
                                @endif
                            @endif
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>

        {{ $tickets->appends(Request::only('search'))->links() }}
    </div>
@stop
