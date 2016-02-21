<table style="width:100%;max-width:540px">
    <tr>
        <td><strong>Order #</strong></td>
        <td>{{ $order->id }}</td>
    </tr>
    <tr>
        <td><strong>Order Date</strong></td>
        <td>{{ $order->created_at->format('M j, Y g:i A') }}</td>
    </tr>

    <tr>
        <td colspan="2" style="padding-bottom:1px;border-top:1px solid #d4d4d4">&nbsp;</td>
    </tr>
    <tr>
        <td><strong>{{ $order->num_tickets }}x</strong> SMMR CMP Tickets</td>
        <td>{{ money_format('%.2n', $order->ticket_total) }}</td>
    </tr>

    @if($order->donation_total > 0)
        <tr>
            <td>SMMR CMP Donation</td>
            <td>{{ money_format('%.2n', $order->donation_total) }}</td>
        </tr>
    @endif

    <tr>
        <td colspan="2" style="padding-bottom:1px;border-top:1px solid #d4d4d4">&nbsp;</td>
    </tr>
    <tr style="font-size:20px">
        <td><strong>Total</strong></td>
        <td>{{ money_format('%.2n', $order->grand_total) }}</td>
    </tr>

    @foreach($order->transactions as $transaction)
        <tr>
            @if($transaction->transaction->card_type)
                <td>{{ $transaction->transaction->card_type }} {{ $transaction->transaction->card_num }}</td>
            @else
                <td>{{ ucwords($transaction->transaction->source) }}</td>
            @endif
            <td>{{ money_format('%.2n', $transaction->amount) }}</td>
        </tr>
    @endforeach

    @if ($order->grand_total > 0)
        <tr>
            <td>Remaining Balance</td>
            <td>{{ money_format('%.2n', $order->balance) }}</td>
        </tr>
    @endif

</table>
