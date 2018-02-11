@extends('layouts.bootstrap4')

@section('content')
    <div class="container-fluid">
        <nav class="nav mb-3">
            <a class="nav-link" href="{{ action('PrinterSelectionController@destroy') }}" onclick="event.preventDefault(); document.getElementById('printer-selection-destroy-form').submit();">Clear Selection</a>
            <a class="nav-link" href="{{ route('printers.destroy') }}" onclick="event.preventDefault(); document.getElementById('printers-destroy-form').submit();">Refresh Printers</a>
        </nav>

        <form action="{{ action('PrinterSelectionController@store') }}" method="POST">
            {{ csrf_field() }}
            <div class="card mb-5">
                <h3 class="card-header">Printers</h3>

                <table class="table table-striped card-text" style="table-layout: fixed">
                    <thead>
                        <tr>
                            <th>Computer</th>
                            <th>Printer</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($printers ?? [] as $printer)
                            <tr>
                                <td>{{ $printer->computer->name }}</td>
                                <td>
                                    {{ $printer->name }}
                                    <ajax href="{{ route('printers.test', $printer->id) }}" method="POST" class="text-muted ml-3" v-cloak>
                                        test
                                        <span slot="success">
                                            @icon('checkmark', 'text-success')
                                        </span>
                                    </ajax>
                                </td>
                                <td>
                                    @unless(session('printer.id') == $printer->id)
                                        <button class="btn btn-primary" name="printer" value="{{ $printer->id }}">Select</button>
                                    @else
                                        <em>Selected</em>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>

        <div class="card">
            <h3 class="card-header">Jobs</h3>
            <table class="table table-striped card-text" style="table-layout: fixed">
                <thead>
                    <tr>
                        <th>Source</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jobs ?? [] as $job)
                        <tr>
                            <td>{{ $job->source }}</td>
                            <td>{{ $job->title }}</td>
                            <td>{{ $job->state }}</td>
                            <td>{{ Carbon\Carbon::parse($job->createTimestamp)->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <form action="{{ action('PrinterSelectionController@destroy') }}" method="POST" id="printer-selection-destroy-form" style="display:none;">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
        </form>
        <form action="{{ route('printers.destroy') }}" method="POST" id="printers-destroy-form" style="display:none;">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
        </form>
    </div>
@stop
