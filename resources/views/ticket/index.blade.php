@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1 class="page-header__title">Attendees</h1>
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

        <table class="table">
            @unless($tickets->count())
                <p><i>No results</i></p>
            @endif
            @foreach ($tickets as $ticket)
                <tr class="{{ $ticket->is_canceled ? 'canceled' : '' }}">
                    <th><a href="{{ route('order.show', $ticket->order) }}">{{ $ticket->name }}</a></th>
                    @if (Auth::user()->is_super_admin)
                        <td>{{ $ticket->organization->church->name }}</td>
                        <td>{{ $ticket->organization->church->location }}</td>
                    @endif
                    <td><span class="label label--{{ $ticket->agegroup }}">{{ ucwords($ticket->agegroup) }} - {{ number_ordinal($ticket->person->grade) }}</span></td>
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
                                {{ $ticket->waiver->status }}
                            @endif
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>

        {{ $tickets->appends(Request::only('search'))->links() }}
    </div>
@stop
