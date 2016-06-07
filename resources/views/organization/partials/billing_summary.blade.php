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
                            {{ $item->name }} <small>({{ number_format($item->quantity) }} @ @currency($item->cost))</small>
                            @can('edit', $item)
                                <a href="{{ route('admin.organization.item.edit', [$organization, $item]) }}">edit</a>
                            @endcan
                        </div>
                        <div class="item right">@currency($item->quantity * $item->cost)</div>
                    </div>
                </li>
            @endforeach
            <li class="callout total">
                <div class="transaction">
                    <div class="item left">Total</div>
                    <div class="item right">@currency($organization->total_cost)</div>
                </div>
            </li>
            <li class="callout total">
                <div class="transaction">
                    <div class="item left">Payments</div>
                    <div class="item right">@currency($organization->total_paid)</div>
                </div>
            </li>
            @if ($organization->deposit_balance > 0)
                <li class="callout deposit_due">
                    <div class="transaction">
                        <div class="item left">Deposit Due</div>
                        <div class="item right">@currency($organization->deposit_balance)</div>
                    </div>
                </li>
            @endif
            <li class="callout balance">
                <div class="transaction">
                    <div class="item left">Balance</div>
                    <div class="item right">@currency($organization->balance)</div>
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
                        @currency($split->amount)
                    </div>
                </div>
                <small class="caption">{{ $split->created_at->toDayDateTimeString() }}</small>
            </li>
        @endforeach
        </ul>
    </div>
    <div class="info-box__content">
        <a href="{{ auth()->user()->isSuperAdmin() ? route('admin.organization.payment.create', $organization) : route('payment.create') }}" class="ui primary tiny button">Make Payment</a>
    </div>
</div>
