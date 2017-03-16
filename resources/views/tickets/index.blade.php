@extends('layouts.bootstrap4')

@section('content')
    <div class="ui container">
        <header class="d-flex justify-content-between">
            <h1>Attendees</h1>
            <p>
                <a href="#" class="btn btn-secondary">Export</a>
            </p>
        </header>

        {{-- <div class="ui padded raised segment">
            <form action="/tickets" method="GET">
                <div class="ui big fluid action input">
                    <input type="search" name="search" class="form-control input-group-field" placeholder="Search..." value="{{ request('search') }}">
                    <button class="ui icon button" type="submit"><i class="search icon"></i></button>
                </div>
            </form>
        </div> --}}

        @unless($tickets->count())
            <p><i>No results</i></p>
        @else
            <table class="ui basic striped table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th></th>
                    <th>Grade</th>
                </tr>
            </thead>
                @foreach ($tickets as $ticket)
                    <tr class="{{ $ticket->is_canceled ? 'canceled' : '' }}">
                        <td><a href="#">{{ $ticket->name }}</a></td>
                        <td>{{ $ticket->organization->church->name }}<br> <small>{{ $ticket->organization->church->location }}</small></td>
                        <td>
                            @include('ticket/partials/label')
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif

        {{-- {{ $tickets->appends(Request::only('search'))->links(new \App\Pagination\Semantic($tickets)) }} --}}
        {{ $tickets->links() }}
    </div>
@stop
