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
            <li class="callout total">
                <div class="transaction">
                    <div class="item left">Total</div>
                    <div class="item right">@currency($order->grand_total)</div>
                </div>
            </li>
            <li class="callout total">
                <div class="transaction">
                    <div class="item left">Payments</div>
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
                        <div class="item left">
                            {{ $split->name }}
                            @if ($split->amount < 0)
                            @elseif ($split->transaction->source == 'stripe')
                                <small><a href="{{ route('transaction.refund.create', $split) }}">refund</a></small>
                            @else
                                <small><a href="{{ route('transaction.edit', $split) }}">edit</a></small>
                            @endif
                        </div>
                        <div class="item right item--{{ $split->amount>0?'success':'warning' }}">@currency($split->amount)</div>
                    </div>
                    @if ($split->transaction->source == 'stripe')
                        <small class="caption">{{ $split->transaction->processor_transactionid }}</small>
                    @endif
                    <small class="caption">@daydatetime($split->created_at)</small>
                </li>
            @endforeach
            </ul>
        </div>
    @endif
</div>
