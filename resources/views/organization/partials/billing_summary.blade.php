<div class="info-box">
    <div class="info-box__title">
        <div>
            <h5>Purchase Summary</h5>
            <p>Registrations and payments <i>made to</i> Passion Camp.</p>
        </div>
    </div>
    <div class="info-box__content">
        <ul class="block-list price-list">
            @foreach ($organization->items as $item)
                <li>
                    <div class="transaction">
                        <div class="item left">
                            {{ $item->name }} <small>({{ number_format($item->quantity) }} @ {{ money_format('%.2n', $item->cost / 100) }})</small>
                            @can('edit', $item)
                                {{-- <a href="{{ route('admin.organization.item.edit', [$organization, $item]) }}">edit</a> --}}
                            @endcan
                        </div>
                        <div class="item right">{{ money_format('%.2n', $item->quantity * $item->cost / 100) }}</div>
                    </div>
                </li>
            @endforeach
            <li class="callout total">
                <div class="transaction">
                    <div class="item left">Total</div>
                    <div class="item right">{{ money_format('%.2n', $organization->total_cost / 100) }}</div>
                </div>
            </li>
            <li class="callout total">
                <div class="transaction">
                    <div class="item left">Payments</div>
                    <div class="item right">{{ money_format('%.2n', $organization->total_paid / 100) }}</div>
                </div>
            </li>
            @if ($organization->deposit_balance > 0)
                <li class="callout deposit_due">
                    <div class="transaction">
                        <div class="item left">Deposit Due</div>
                        <div class="item right">{{ money_format('%.2n', $organization->deposit_balance / 100) }}</div>
                    </div>
                </li>
            @endif
            <li class="callout balance">
                <div class="transaction">
                    <div class="item left">Balance</div>
                    <div class="item right">{{ money_format('%.2n', $organization->balance / 100) }}</div>
                </div>
            </li>
        </ul>
    </div>
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
                        {{ money_format('%.2n', $split->amount / 100) }}
                    </div>
                </div>
                @if ($split->transaction->source == 'stripe')
                    <small class="caption">{{ $split->transaction->identifier }}</small>
                @endif
                <small class="caption">{{ $split->created_at->toDayDateTimeString() }}</small>
            </li>
        @endforeach
        </ul>
    </div>
</div>
