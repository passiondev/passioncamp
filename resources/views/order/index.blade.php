@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1 class="page-header__title">Registrations</h1>
            <div class="page-header__actions">
                <a href="/registration/create" class="button small">Add Registration</a>
            </div>
        </header>

        <div class="callout primary">
            <form action="/registrations" method="GET">
                <div class="form-group input-group">
                    <input type="search" name="search" class="form-control input-group-field" placeholder="Search..." value="{{ request('search') }}">
                    <div class="input-group-button">
                        <button type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>

        <div>
            @unless($orders->count())
                <p><i>No results</i></p>
            @endif
            @foreach($orders as $order)
                <a href="{{ route('order.show', $order) }}" class="panel panel--registration">
                    <div class="panel-body">
                        @unless($order->tickets->count() > 0)
                            <p><i>no tickets</i></p>
                        @else
                            <table class="table">
                                <tbody>
                                    @foreach($order->tickets as $ticket)
                                        <tr class="{{ $ticket->is_canceled ? 'canceled' : '' }}">
                                            <th>{{ $ticket->person->name }}</th>
                                            <td>
                                                @include('ticket/partials/label')
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                    <aside class="panel-side"></aside>
                    <footer class="panel-footer">
                        <ul class="footer-meta">
                            @auth('owner')
                                <li class="meta--church">{{ $order->organization->church->name }}</li>
                            @endif
                            <li class="meta--registration">Registration #{{ $order->id }}</li>
                            <li class="meta--created">{{ $order->created_at->format('M j, Y g:ia') }}</li>
                        </ul>
                        <div class="footer-actions">
                            <span class="button xsmall">more info</span>
                        </div>
                    </footer>
                </a>
            @endforeach
            {{ $orders->appends(Request::only('search'))->links() }}
        </div>
    </div>
@stop
