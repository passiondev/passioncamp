@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Registrations</h1>
        </header>

        <form action="/admin/orders" method="GET">
            <div class="form-group">
                <input type="search" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
            </div>
        </form>

        <div>
            @unless($orders->count())
                <p><i>No results</i></p>
            @endif
            @foreach($orders as $order)
                <a href="{{ route('admin.order.show', $order) }}">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-8">
                                    @unless($order->tickets->count() > 0)
                                        <i>no tickets</i>
                                    @else
                                        <table class="table table-striped">
                                            <tbody>
                                                @foreach($order->tickets as $ticket)
                                                    <tr>
                                                        <th>{{ $ticket->person->name }}</th>
                                                        <td>{{ $ticket->agegroup }}</td>
                                                        <td>@ordinal($ticket->person->grade)</td>
                                                        <td>@currency($ticket->price)</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                    <div>Registration #{{ $order->id }} - {{ $order->created_at->toDayDateTimeString() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
            {{ $orders->appends(Request::only('search'))->links() }}
        </div>
    </div>
@stop
