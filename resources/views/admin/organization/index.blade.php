@extends('layouts.semantic')

@section('content')
    <div class="ui container">
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
                    <th>Location</th>
                    <th>Contact</th>
                    <th>Balance</th>
                    <th>Tickets</th>
                    <th>Registered</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($organizations as $organization)
                    <tr>
                        <td>
                            {{ link_to_route('admin.organization.show', $organization->church->name, $organization) }}
                            <small style="display:block;font-weight: normal;color:#aaa">{{ $organization->created_at->format('M j, Y g:i A') }}</small>
                        </td>
                        <td>
                            {{ $organization->church->location }}
                        </td>
                        <td>
                            @if ($organization->contact)
                                {{ $organization->contact->name }} <br> <small>{{ $organization->contact->email }}</small>
                            @endif
                        </td>
                        <td>{{ money_format('$%.2n', $organization->balance) }}</td>
                        <td>
                            {{ $organization->num_tickets }}
                        </td>
                        <td>{{ $organization->attendees->active()->count() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
