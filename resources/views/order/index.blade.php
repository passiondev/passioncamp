@extends('layouts.semantic')

@section('content')
    <div class="ui container" style="padding-bottom:3rem">
        <header class="ui dividing header page-header">
            <h1 class="page-header__title">Registrations</h1>
            <div class="sub header page-header__actions">
                <a href="/registration/create" class="button small">Add Registration</a>
            </div>
        </header>

        <div class="ui padded raised segment">
            <form action="/registrations" method="GET">
                <div class="ui big fluid action input">
                    <input type="search" name="search" class="form-control input-group-field" placeholder="Search..." value="{{ request('search') }}">
                    <button class="ui icon button" type="submit"><i class="search icon"></i></button>
                </div>
            </form>
        </div>

        <div>
            @unless($orders->count())
                <p><i>No results</i></p>
            @endif
            @foreach($orders as $order)
                <a href="{{ route('order.show', $order) }}" class="ui segment" style="display:block">
                    <div class="panel-body" style="margin-bottom:1rem">
                        @unless($order->tickets->count() > 0)
                            <p><i>no tickets</i></p>
                        @else
                            <table class="ui fixed unstackable table">
                                <tbody>
                                    @foreach($order->tickets as $ticket)
                                        <tr class="{{ $ticket->is_canceled ? 'canceled' : '' }}">
                                            <td>{{ $ticket->person->name }}</td>
                                            <td>
                                                @include('ticket/partials/label')
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                    <footer>
                        <div style="display:flex;justify-content:space-between;align-items:center">
                            <div class="ui mini primary button">more info</div>
                            <div>
                                @if (Auth::user()->isSuperAdmin())
                                    <span class="">{{ $order->organization->church->name }}</span> •
                                @endif
                                <span class="">Registration #{{ $order->id }}</span> •
                                <span class="">{{ $order->created_at->format('M j, Y g:ia') }}</span>
                            </div>
                        </div>
                    </footer>
                </a>
            @endforeach
            {{ $orders->appends(Request::only('search'))->links(new \App\Pagination\Semantic($orders)) }}
        </div>
    </div>
@stop
