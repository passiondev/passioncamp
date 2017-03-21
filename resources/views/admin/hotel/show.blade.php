@extends('layouts.bootstrap4')

@section('content')
    <div class="container">
        <header>
            <h1>{{ $hotel->name }}</h1>
        </header>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Church Name</th>
                    <th>Rooms</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hotel->organizations as $organization)
                    <tr>
                        <td><a href="{{ action('Super\OrganizationController@show', $organization) }}">{{ $organization->church->name }}</a></td>
                        <td>{{ $organization->roomCountForHotel($hotel) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
