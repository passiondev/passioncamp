<div class="info-box">
    <div class="info-box__title">
        <h5>Registration Summary</h5>
    </div>
    <div class="info-box__content">
        <ul class="block-list price-list">
            <li>
                <div class="transaction">
                    <div class="item left">Attendees <span class="badge badge-pill badge-success">{{ $order->user->ticket_count }}</span></div>
                    <div class="item right">{{ money_format('%(.2n', $order->user->ticket_total / 100) }}</div>
                </div>
            </li>
            @if ($order->user->donation_total > 0)
                <li>
                    <div class="transaction">
                        <div class="item left">Donation</div>
                        <div class="item right">{{ money_format('%(.2n', $order->user->donation_total / 100) }}</div>
                    </div>
                </li>
            @endif
            <li class="callout total">
                <div class="transaction">
                    <div class="item left">Total</div>
                    <div class="item right">{{ money_format('%(.2n', $order->user->grand_total / 100) }}</div>
                </div>
            </li>
            <li class="callout total">
                <div class="transaction">
                    <div class="item left">Payments</div>
                    <div class="item right">{{ money_format('%(.2n', $order->user->transactions_total / 100) }}</div>
                </div>
            </li>
            <li class="callout balance">
                <div class="transaction">
                    <div class="item left">Balance</div>
                    <div class="item right">{{ money_format('%(.2n', $order->user->balance / 100) }}</div>
                </div>
            </li>
        </ul>
    </div>
    @if ($order->user->transactions->count() > 0)
        <div class="info-box__title">
            <h5>Transactions</h5>
        </div>
        <div class="info-box__content">
            <ul class="block-list price-list">
            @foreach ($order->user->transactions as $split)
                <li>
                    <div class="transaction">
                        <div class="item left">
                            {{ $split->name }}
                            @unless (auth()->user()->isOrderOwner())

                                @if ($split->transaction->source == 'stripe' && $split->amount > 0)
                                    <small><a href="{{ action('TransactionRefundController@create', $split) }}">refund</a></small>
                                @endif

                                @if ($split->transaction->source != 'stripe')
                                    <small><a href="{{ action('TransactionController@edit', $split) }}">edit</a></small>
                                @endif

                            @endunless
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
