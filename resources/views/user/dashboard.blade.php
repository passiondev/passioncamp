@extends('layouts.bootstrap4')

@section('content')
<div class="container">
    @if ($user->balance > 0)
        <div class="alert alert-warning">
            <p>You have a balance of {{ money_format('%.2n', $user->balance / 100) }} due. <a href="{{ action('User\PaymentsController@index') }}">Click here to make a payment.</a></p>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <header class="card-header">
                    <h4>Attendees</h4>
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
                                <td>{{ $ticket->name }}</td>
                                <td>
                                    @include('ticket/partials/label')
                                </td>
                                <td>{{ money_format('%.2n', $ticket->price / 100) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-box">
                <div class="info-box__title">
                    <h5>Registration Summary</h5>
                </div>
                <div class="info-box__content">
                    <ul class="block-list price-list">
                        <li>
                            <div class="transaction">
                                <div class="item left">Attendees <span class="badge badge-pill badge-success">{{ $user->tickets_count }}</span></div>
                                <div class="item right">{{ money_format('%(.2n', $user->ticket_total / 100) }}</div>
                            </div>
                        </li>
                        @if ($user->donation_total > 0)
                            <li>
                                <div class="transaction">
                                    <div class="item left">Donation</div>
                                    <div class="item right">{{ money_format('%(.2n', $user->donation_total / 100) }}</div>
                                </div>
                            </li>
                        @endif
                        <li class="callout total">
                            <div class="transaction">
                                <div class="item left">Total</div>
                                <div class="item right">{{ money_format('%(.2n', $user->grand_total / 100) }}</div>
                            </div>
                        </li>
                        <li class="callout total">
                            <div class="transaction">
                                <div class="item left">Payments</div>
                                <div class="item right">{{ money_format('%(.2n', $user->transactions_total / 100) }}</div>
                            </div>
                        </li>
                        <li class="callout balance">
                            <div class="transaction">
                                <div class="item left">Balance</div>
                                <div class="item right">{{ money_format('%(.2n', $user->balance / 100) }}</div>
                            </div>
                        </li>
                    </ul>
                </div>
                @if ($user->transactions->count() > 0)
                    <div class="info-box__title">
                        <h5>Transactions</h5>
                    </div>
                    <div class="info-box__content">
                        <ul class="block-list price-list">
                        @foreach ($user->transactions as $split)
                            <li>
                                <div class="transaction">
                                    <div class="item left">
                                        {{ $split->name }}
                                    </div>
                                    <div class="item right item--{{ $split->amount>0?'success':'warning' }}">{{ money_format('%(.2n', $split->amount / 100) }}</div>
                                </div>
                                @if ($split->transaction->source == 'stripe')
                                    <small class="caption">{{ $split->transaction->identifier }}</small>
                                @endif
                                <small class="caption">@daydatetime($split->created_at)</small>
                            </li>
                        @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
@stop
