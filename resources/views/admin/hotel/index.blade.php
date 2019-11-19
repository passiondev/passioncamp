@extends('layouts.bootstrap4')

@section('content')
    <div class="container-fluid">
        <header class="page-header">
            <h1>Hotels</h1>
        </header>
        <table class="table table-responsive table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th class="text-center">Churches</th>
                    <th class="text-center">Purchased</th>
                    {{-- <th class="text-center">Capacity</th> --}}
                    {{-- <th class="text-center">Remaining</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($hotels as $hotel)
                    <tr>
                        <td><a href="{{ route('admin.hotels.show', $hotel) }}">{{ $hotel->name }}</a></td>
                        <td class="text-center">{{ $hotel->organizations_count }}</td>
                        <td class="text-center">{{ $hotel->purchased_sum }}</td>
                        {{-- <td class="text-center">{{ number_format($hotel->capacity) }}</td> --}}
                        {{-- <td class="text-center">{{ $hotel->remaining_count }}</td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
