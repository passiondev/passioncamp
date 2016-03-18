@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Churches</h1>
            <a href="{{ route('admin.organization.create') }}">Add Church</a>
        </header>

        <table class="table">
            <thead>
                <tr>
                    <th>Church</th>
                    <th>Contact</th>
                    <th>Location</th>
                    <th>Tickets</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($organizations as $organization)
                    <tr>
                        <th>
                            {{ link_to_route('admin.organization.show', $organization->church->name, $organization) }}
                        </th>
                        <td>
                            {{ $organization->church->location }}
                        </td>
                        <td>
                            @if ($organization->contact)
                                {{ $organization->contact->name }} <small>({{ $organization->contact->email }})</small>
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
