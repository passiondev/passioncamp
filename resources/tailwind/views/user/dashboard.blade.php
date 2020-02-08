@extends('layouts.bootstrap4')

@section('content')
<div class="container">
    @if ($user->balance > 0)
        <div class="alert alert-warning">
            <p>You have a balance of {{ \Money\Money::USD($user->balance / 100) }} due. <a href="{{ route('user.payments.index') }}">Click here to make a payment.</a></p>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <header class="card-header">
                    <h4>Students</h4>
                </header>
                <table class="table table-responsive">
                    <thead class="mobile hidden">
                        <tr>
                            <th>Name</th>
                            <th></th>
                            <th>Ticket Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                            <tr class="{{ $ticket->is_canceled ? 'canceled' : '' }}">
                                <td>
                                    <h1 style="font-size: 1.2rem" class="mb-0">
                                        {{ $ticket->name }}
                                    </h1>
                                    @include('ticket/partials/label')
                                </td>
                                <td>
                                    {{ $ticket->order->organization->church->name }}
                                </td>
                                <td>{{ \Money\Money::USD($ticket->price / 100) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            @include('order/partials/registration_summary', ['user' => $user])
        </div>
    </div>

</div>
@stop
