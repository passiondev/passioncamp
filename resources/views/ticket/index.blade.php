@extends('layouts.bootstrap4')

@section('content')
    <div class="ui container">
        <header class="d-flex justify-content-between">
            <h1>Attendees</h1>
            <div>
                @unless (auth()->user()->isSuperAdmin())
                    <a href="{{ action('Account\TicketController@create') }}" class="btn btn-primary">Add Attendee</a>
                @endunless
            </div>
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
                    @if (auth()->user()->isSuperAdmin())
                        <th></th>
                    @endif
                    <th>Grade</th>
                    <th></th>
                </tr>
            </thead>
                @foreach ($tickets as $ticket)
                    <tr class="{{ $ticket->is_canceled ? 'canceled' : '' }}">
                        <td>
                            @if ($ticket->organization->slug == 'pcc')
                                <a href="{{ action('OrderController@show', $ticket->order) }}">{{ $ticket->name }}</a>
                            @else
                                {{ $ticket->name }}
                            @endif
                        </td>
                        @if (auth()->user()->isSuperAdmin())
                            <td>{{ $ticket->organization->church->name }}<br> <small>{{ $ticket->organization->church->location }}</small></td>
                        @endif
                        <td>
                            @include('ticket/partials/label')
                        </td>
                        <td>
                            <a href="{{ action('TicketController@edit', $ticket) }}" class="btn btn-outline-primary btn-sm">edit</a>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif

        {{-- {{ $tickets->appends(Request::only('search'))->links(new \App\Pagination\Semantic($tickets)) }} --}}
        {{ $tickets->links() }}
    </div>
@stop
