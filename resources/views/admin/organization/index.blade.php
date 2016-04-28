@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1 class="page-header__title">
                Churches
                <span class="label">{{ $organizations->count() }} Churches</span>
                <span class="label">{{ $organizations->sumTicketQuantity() }} Tickets</span>
            </h1>
            <div class="page-header__actions">
                <a class="button small" href="{{ route('admin.organization.create') }}">Add Church</a>
            </div>
        </header>

        <table class="table">
            <thead>
                <tr>
                    <th>Church</th>
                    <th>Location</th>
                    <th>Contact</th>
                    <th>Tickets</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($organizations as $organization)
                    <tr>
                        <th>
                            {{ link_to_route('admin.organization.show', $organization->church->name, $organization) }}
                            <small style="display:block;font-weight: normal;color:#aaa">{{ $organization->created_at->format('M j, Y g:i A') }}</small>
                        </th>
                        <td>
                            {{ $organization->church->location }}
                        </td>
                        <td>
                            @if ($organization->contact)
                                {{ $organization->contact->name }} <br> <small>{{ $organization->contact->email }}</small>
                            @endif
                        </td>
                        <td>
                            {{ $organization->num_tickets }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
