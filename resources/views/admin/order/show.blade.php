@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Registration #{{ $order->id }}</h1>
        </header>
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

        <div class="info-box">
            <div class="info-box__title">
                <h5>Registration Summary</h5>
            </div>
            <div class="info-box__content">
                <ul class="block-list price-list">
                    <li>
                        <div class="transaction">
                            <div class="item left">Attendees <span class="label-info label round">{{ $order->ticket_count }}</span></div>
                            <div class="item right">@currency($order->ticket_total)</div>
                        </div>
                    </li>
                    @if ($order->donation_total > 0)
                        <li>
                            <div class="transaction">
                                <div class="item left">Donation</div>
                                <div class="item right">@currency($order->donation_total)</div>
                            </div>
                        </li>
                    @endif
                    <li class="callout paid">
                        <div class="transaction">
                            <div class="item left">Total</div>
                            <div class="item right">@currency($order->grand_total)</div>
                        </div>
                    </li>
                    <li class="callout paid">
                        <div class="transaction">
                            <div class="item left">Paid</div>
                            <div class="item right">@currency($order->transactions_total)</div>
                        </div>
                    </li>
                    <li class="callout balance">
                        <div class="transaction">
                            <div class="item left">Balance</div>
                            <div class="item right">@currency($order->balance)</div>
                        </div>
                    </li>
                </ul>
            </div>
            @if ($order->transactions->count() > 0)
                <div class="info-box__title">
                    <h5>Transactions</h5>
                </div>
                <div class="info-box__content">
                    <ul class="block-list price-list">
                    @foreach ($order->transactions as $split)
                        <li>
                            <div class="transaction">
                                <div class="item left">{{ $split->name }}</div>
                                <div class="item right item--{{ $split->amount>0?'success':'warning' }}">@currency($split->amount)</div>
                            </div>
                            <small class="caption">@daydatetime($split->created_at)</small>
                        </li>
                    @endforeach
                    </ul>
                </div>
            @endif
            <div class="info-box__content">
                @if($order->organization->slug == 'pcc' && $order->balance < 0)
                    <a href="" class="button small alert">Process Refund</a>
                @endif
                <a href="" class="button small">Record Transacation</a>
            </div>
        </div>
    </div>
@stop
