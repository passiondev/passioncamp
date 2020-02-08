@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <div class="ui stackable grid">
            <div class="five wide column">
                <h4>Contact</h4>
                <dl>
                    <dt>{{ auth()->user()->person->name }}</dt>
                    <dd>{{ auth()->user()->person->phone }}</dd>
                    <dd>{{ auth()->user()->person->email }}</dd>
                </dl>
                <p><a href="{{ route('profile') }}" class="ui mini basic blue button">edit</a></p>
            </div>
            <div class="eleven wide column">
                <h4>Registrations</h4>
                <table class="ui unstackable table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Registered On</th>
                            <th class="center aligned"># Tickets</th>
                            <th class="right aligned">Balance Due</th>
                        </tr>
                    </thead>
                    @foreach (auth()->user()->orders as $order)
                        <tr>
                            <td><a href="{{ route('order.show', $order) }}">Registration #{{ $order->id }}</a></td>
                            <td>{{ $order->created_at->toDayDateTimeString() }}</td>
                            <td class="center aligned">{{ $order->activeTickets->count() }}</td>
                            <td class="right aligned">{{ \Money\Money::USD($order->balance) }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@stop
