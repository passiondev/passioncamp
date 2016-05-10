@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <h1>{{ $hotel->name }}</h1>
        </header>

        <table class="ui very basic fixed table">
            <thead>
                <tr>
                    <th>Church Name</th>
                    <th>Rooms</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hotel->organizations as $organization)
                    <tr>
                        <td><a href="{{ route('admin.organization.show', $organization) }}">{{ $organization->church->name }}</a></td>
                        <td>{{ $organization->roomCountForHotel($hotel) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
