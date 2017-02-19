<table class="invoice-wrapper" width="100%" cellpadding="0" cellspacing="0" style="width: 100%;margin: 1.5em 0;">
    <tr>
        <td>
            <h4 style="margin:1em 0;color: #2F3133;text-align: left;font-size: 14px;">Order #{{ $order->id }}</h4>
        </td>
        <td>
            <h4 class="align-right" style="text-align: right;margin: 1em 0;color: #2F3133;font-size: 14px;">{{ $order->created_at->format('M j, Y g:ia')}}</h4>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <table class="invoice-table" width="100%" cellpadding="0" cellspacing="0" style="width: 100%;margin: 0;padding: 25px 0 0 0;">

                <tr>
                    <td width="80%" style="padding: 10px 0;font-size:14px;border-top: 1px solid #EDEFF2;">SMMR CMP Ticket x {{ $order->activeTickets->count() }}</td>
                    <td class="align-right" width="20%" style="text-align: right;padding: 10px 0;font-size:14px;border-top: 1px solid #EDEFF2;">{{ money_format('%.2n', $order->ticket_total / 100) }}</td>
                </tr>

                @if($order->donation_total > 0)
                    <tr>
                        <td width="80%" style="padding: 10px 0;font-size:14px">Donation</td>
                        <td class="align-right" width="20%" style="text-align: right;padding: 10px 0;font-size:14px">{{ money_format('%.2n', $order->donation_total / 100) }}</td>
                    </tr>
                @endif

                <tr>
                    <td width="80%" class="total-cell" valign="middle" style="border-top: 1px solid #EDEFF2;padding: 15px 0 10px 0;">
                        <p class="total-cell_label" style="padding: 0 15px 0 0;margin: 0;text-align: left;font-weight: bold;margin-top: 0;">Total</p>
                    </td>
                    <td width="20%" class="total-cell" valign="middle" style="border-top: 1px solid #EDEFF2;padding: 15px 0 10px 0;">
                        <p style="margin: 0;text-align: right;font-weight: bold;margin-top: 0;">{{ money_format('%.2n', $order->grand_total / 100) }}</p>
                    </td>
                </tr>

                @foreach($order->transactions as $transaction)
                    <tr>
                        <td width="80%" class="total-cell" valign="middle" style="padding: 2px 0;font-size:14px">
                            <p style="margin: 0;text-align: right;padding-right:15px">
                                @if($transaction->transaction->cc_brand)
                                    {{ $transaction->transaction->cc_brand }} {{ $transaction->transaction->cc_last4 }}
                                @else
                                    {{ ucwords($transaction->transaction->source) }}
                                @endif
                            </p>
                        </td>
                        <td width="20%" class="total-cell" valign="middle" style="padding: 2px 0;font-size:14px">
                            <p style="margin: 0;text-align: right;">
                                {{ money_format('%.2n', $transaction->amount / 100) }}
                            </p>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td width="80%" class="total-cell" valign="middle" style="padding: 10px 0;font-size:14px">
                        <p style="margin: 0;text-align: right;font-weight: bold;padding-right:15px">
                            Balance Due
                        </p>
                    </td>
                    <td width="20%" class="total-cell" valign="middle" style="padding: 10px 0;font-size:14px">
                        <p style="margin: 0;text-align: right;font-weight: bold">
                            {{ money_format('%.2n', $order->balance / 100) }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
