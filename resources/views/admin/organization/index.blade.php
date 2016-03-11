@extends('layouts.app')

@section('content')
    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th>Ministry</th>
                    <th>Contact</th>
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
