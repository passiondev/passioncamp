@extends('layouts.bootstrap4')

@section('content')
    <div class="container">
        <header class="d-flex justify-content-between">
            <h1>Churches</h1>
            <p>
                <a class="btn btn-secondary" href="{{ action('Super\OrganizationController@create') }}">Add Church</a>
            </p>
        </header>

        <table class="table table-responsive table-striped" style="table-layout: fixed;width:100%">
            <thead>
                <tr>
                    <th>Church</th>
                    <th>Contact</th>
                    <th>Balance</th>
                    <th class="text-center">Tickets</th>
                    <th class="text-center">Registered</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($organizations as $organization)
                    <tr>
                        <td>
                                <a href="{{ action('Super\OrganizationController@show', $organization) }}">{{ $organization->church->name }}</a>
                                <div>
                                    {{ $organization->church->location }}
                                    <small style="display:block;font-weight: normal;color:#aaa">{{ $organization->created_at->format('M j, Y g:i A') }}</small>
                                </div>
                        </td>
                        <td>
                            @if ($organization->contact)
                                {{ $organization->contact->name }} <br> <small>{{ $organization->contact->email }}</small>
                            @endif
                        </td>
                        <td>{{ money_format('%.0n', $organization->balance / 100) }}</td>
                        <td class="text-center">{{ $organization->num_tickets }}</td>
                        <td class="text-center" class="{{ $organization->attendees->active()->count() == $organization->num_tickets ? 'positive' : '' }} {{ $organization->attendees->active()->count() > $organization->num_tickets ? 'negative' : '' }}">{{ $organization->attendees->active()->count() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $organizations->links() }}
    </div>
@stop
