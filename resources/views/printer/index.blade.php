@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <a href="{{ route('printer.reset') }}">reset</a>
        @if (count($jobs) > 0)
            <table class="ui fixed striped table">
                @foreach ($jobs as $job)
                    <tr>
                        <td>{{ $job->title }}</td>
                        <td>{{ $job->state }}</td>
                        <td>{{ $job->createTimestamp->format('c') }}</td>
                    </tr>
                @endforeach
            </table>
        @endif
        @include('printer.partials.chooser-table')
    </div>
@stop
