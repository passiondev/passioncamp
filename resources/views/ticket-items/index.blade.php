@extends('layouts.bootstrap4')

@section('content')
    <div class="container-fluid">
        <header class="page-header">
            <h1>Tickets</h1>
        </header>
        <table class="table table-responsive table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th class="text-center">Purchased</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td class="text-center">{{ $item->purchased_sum }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop
