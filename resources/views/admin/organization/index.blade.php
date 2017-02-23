@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <div class="ui statistic">
            <div class="value">{{ money_format("%.2n", App\Organization::totalPaid()) }}</div>
            <div class="label">Total Paid</div>
        </div>
        <div class="ui mini statistic">
            <div class="value">{{ money_format("%.2n", App\Organization::totalPaid('stripe')) }}</div>
            <div class="label">Stripe</div>
        </div>
        <div class="ui mini statistic">
            <div class="value">{{ money_format("%.2n", App\Organization::totalPaid('payscape')) }}</div>
            <div class="label">Payscape</div>
        </div>
        <div class="ui mini statistic">
            <div class="value">{{ money_format("%.2n", App\Organization::totalPaid('other')) }}</div>
            <div class="label">Check / Other</div>
        </div>

        <hr class="ui divider">

        <header class="page-header">
            <h1 class="page-header__title">
                Churches
                <div class="ui label purple">{{ $organizations->count() }} <div class="detail">Churches</div></div>
                <div class="ui label teal">{{ $organizations->sumTicketQuantity() }} <div class="detail">Tickets</div></div>
            </h1>
            <div class="page-header__actions">
                <a href="{{ route('admin.organization.create') }}">Add Church</a>
            </div>
        </header>

        <table class="ui very basic striped table">
            <thead>
                <tr>
                    <th>Church</th>
                    <th>Contact</th>
                    <th>Cost</th>
                    <th>Paid</th>
                    <th>Balance</th>
                    <th style="text-align:center">Tickets</th>
                    <th style="text-align:center">Registered</th>
                    <th style="text-align:center">Signed<br>Waivers</th>
                    <th style="text-align:center">Assigned<br>To Room</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($organizations as $organization)
                    <tr>
                        <td>
                            <h4 class="ui header">
                                {{ link_to_route('admin.organization.show', $organization->church->name, $organization) }}
                                <div class="sub header">
                                    {{ $organization->church->location }}
                                    <small style="display:block;font-weight: normal;color:#aaa">{{ $organization->created_at->format('M j, Y g:i A') }}</small>
                                </div>
                            </h4>
                        </td>
                        <td>
                            @if ($organization->contact)
                                {{ $organization->contact->name }} <br> <small>{{ $organization->contact->email }}</small>
                            @endif
                        </td>
                        <td>{{ money_format('%.2n', $organization->total_cost) }}</td>
                        <td>{{ money_format('%.2n', $organization->total_paid) }}</td>
                        <td>{{ money_format('%.2n', $organization->balance) }}</td>
                        <td style="text-align:center">{{ $organization->num_tickets }}</td>
                        <td style="text-align:center" class="{{ $organization->attendees->active()->count() == $organization->num_tickets ? 'positive' : '' }} {{ $organization->attendees->active()->count() > $organization->num_tickets ? 'negative' : '' }}">{{ $organization->attendees->active()->count() }}</td>
                        <td style="text-align:center" class="{{ $organization->attendees->active()->count() == $organization->signed_waivers_count ? 'positive' : 'negative' }}">{{ $organization->signed_waivers_count }}</td>
                        <td style="text-align:center" class="{{ $organization->attendees->active()->count() == $organization->assigned_to_room_count ? 'positive' : 'negative' }}">{{ $organization->assigned_to_room_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
