<div class="info-box">
    <div class="info-box__title">
        <div>
            <h5>Registration Summary</h5>
            <p>Registrations and payments <i>collected by</i> {{ $organization->church->name }}.</p>
        </div>
    </div>
    <div class="info-box__content">
        <ul class="block-list price-list">
            <li>
                <div class="transaction">
                    <div class="item left">
                        Attendees <span class="ui blue circular label">{{ number_format($organization->orders->ticket_count) }}</small>
                    </div>
                    <div class="item right">@currency($organization->orders->ticket_total)</div>
                </div>
            </li>
            @if ($organization->orders->donation_total > 0)
                <li>
                    <div class="transaction">
                        <div class="item left">
                            Donations
                        </div>
                        <div class="item right">@currency($organization->orders->donation_total)</div>
                    </div>
                </li>
            @endif
            <li class="callout total">
                <div class="transaction">
                    <div class="item left">Total</div>
                    <div class="item right">@currency($organization->orders->grand_total)</div>
                </div>
            </li>
            <li class="callout total">
                <div class="transaction">
                    <div class="item left">Payments</div>
                    <div class="item right">@currency($organization->orders->transaction_total)</div>
                </div>
            </li>
            <li class="callout balance">
                <div class="transaction">
                    <div class="item left">Balance</div>
                    <div class="item right">@currency($organization->orders->balance)</div>
                </div>
            </li>
        </ul>
    </div>
</div>
