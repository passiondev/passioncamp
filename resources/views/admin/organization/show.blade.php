@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>{{ $organization->church->name }}</h1>
        </header>

        <div class="row">
            <div class="col-sm-4">
                <h5>Church</h5>
                <dl>
                    <dt>{{ $organization->church->name }}</dt>
                    <dd>{{ $organization->church->street }}<br>{{ $organization->church->city }}, {{ $organization->church->state }} {{ $organization->church->zip }}</dd>
                    <dd>{{ $organization->church->website }}</dd>
                    <dd>{{ $organization->church->pastor_name }}</dd>
                </dl>
            </div>
            <div class="col-sm-4">
                <h5>Contact</h5>
                <dl>
                    <dt>{{ $organization->contact->name }}</dt>
                    <dd>{{ $organization->contact->email }}</dd>
                    <dd>{{ $organization->contact->phone }}</dd>
                    <dd>{{ $organization->contact_desciption }}</dd>
                </dl>
            </div>
            <div class="col-sm-4">
                <h5>Student Pastor</h5>
                <dl>
                    <dt>{{ $organization->studentPastor->name }}</dt>
                    <dd>{{ $organization->studentPastor->email }}</dd>
                    <dd>{{ $organization->studentPastor->phone }}</dd>
                </dl>
            </div>
        </div>

        <div class="info-box">
            <div class="info-box__title">
                <h5>Transaction Summary</h5>
            </div>
            <div class="info-box__content">
                <ul class="block-list price-list">
                    @foreach ($organization->items as $item)
                        <li>
                            <div class="transaction">
                                <div class="item left">
                                    {{ $item->name }} <small>({{ number_format($item->quantity) }} @ {{ money_format('%.2n', $item->cost) }})</small>
                                </div>
                                <div class="item right">{{ money_format('%.2n', $item->quantity * $item->cost) }}</div>
                            </div>
                        </li>
                    @endforeach
                    <li class="callout paid">
                        <div class="transaction">
                            <div class="item left">Total</div>
                            <div class="item right">{{ money_format('%.2n', $organization->total_cost) }}</div>
                        </div>
                    </li>
                    <li class="callout paid">
                        <div class="transaction">
                            <div class="item left">Paid</div>
                            <div class="item right">{{ money_format('%.2n', $organization->total_paid) }}</div>
                        </div>
                    </li>
                    @if ($organization->deposit_balance > 0)
                        <li class="callout deposit_due">
                            <div class="transaction">
                                <div class="item left">Deposit Due</div>
                                <div class="item right">{{ money_format('%.2n', $organization->deposit_balance) }}</div>
                            </div>
                        </li>
                    @endif
                    <li class="callout balance">
                        <div class="transaction">
                            <div class="item left">Balance</div>
                            <div class="item right">{{ money_format('%.2n', $organization->balance) }}</div>
                        </div>
                    </li>
                </ul>
            </div>
            @if ($organization->transactions->count() > 0)
                <div class="info-box__title">
                    <h5>Payments</h5>
                </div>
                <div class="info-box__content">
                    <ul class="block-list price-list">
                    @foreach ($organization->transactions as $split)
                        <li>
                            <div class="transaction">
                                <div class="item left">
                                    {{ $split->name }}
                                </div>
                                <div class="item right item--{{ $split->amount>0 ? 'success' : 'warning' }}">
                                    {{ money_format('%.2n', $split->amount) }}
                                </div>
                            </div>
                            <small class="caption">{{ $split->created_at->toDayDateTimeString() }}</small>
                        </li>
                    @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
@stop
