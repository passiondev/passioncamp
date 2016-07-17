@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="ui dividing header page-header">
            <h1 class="page-header__title">Attendees</h1>
            <div class="sub header page-header__actions">
                <a href="{{ route('ticket.export.index') }}">Export</a>
            </div>
        </header>

        <div class="ui padded raised segment">
            <form action="/tickets" method="GET">
                <div class="ui big fluid action input">
                    <input type="search" name="search" class="form-control input-group-field" placeholder="Search..." value="{{ request('search') }}">
                    <button class="ui icon button" type="submit"><i class="search icon"></i></button>
                </div>
            </form>
        </div>

        @unless($tickets->count())
            <p><i>No results</i></p>
        @else
            <table class="ui basic striped table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Grade</th>
                    @can ('record-transactions', $tickets->first()->organization)
                        <th>Price</th>
                    @endcan
                    <th>Waiver</th>
                </tr>
            </thead>
                @foreach ($tickets as $ticket)
                    <tr class="{{ $ticket->is_canceled ? 'canceled' : '' }}">
                        <td><a href="{{ route('order.show', $ticket->order) }}">{{ $ticket->name }}</a></td>
                        @if (Auth::user()->isSuperAdmin())
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
                                    <h4 class="ui header">
                                        @unless ($ticket->is_canceled)
                                            {!! $ticket->waiver->is_complete ? '<span style="font-weight:normal"><i class="green checkmark icon"></i>'.$ticket->waiver->status.'</span>' : $ticket->waiver->status !!}<br>
                                        @endunless
                                        @unless ($ticket->waiver->status == 'signed')
                                            <div class="sub header">
                                                <Waiver inline-template>
                                                    <a href="{{ route('ticket.waiver.reminder', $ticket) }}">send reminder</a>
                                                </Waiver>
                                                @if (Auth::user()->isSuperAdmin())
                                                    <a href="{{ route('ticket.waiver.cancel', $ticket) }}">cancel</a>
                                                    <a href="{{ route('ticket.waiver.complete', $ticket) }}">complete</a>
                                                @endif
                                            </div>
                                        @endif
                                    </h4>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif

        {{-- {{ $tickets->appends(Request::only('search'))->links(new \App\Pagination\Semantic($tickets)) }} --}}
    </div>
@stop
