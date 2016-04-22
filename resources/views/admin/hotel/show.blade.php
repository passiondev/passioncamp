@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>{{ $hotel->name }}</h1>
        </header>

        <table class="table">
            <thead>
                <tr>
                    <th>Church Name</th>
                    <th>Rooms</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($hotel->organizations as $organization)
                    <tr>
                        <th><a href="{{ route('admin.organization.show', $organization) }}">{{ $organization->church->name }}</a></th>
                        <td>{{ $organization->roomCountForHotel($hotel) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
