@extends('layouts.semantic')

@section('content')
    <div class="ui container">
    <pre>{{ request()->route()->getPrefix() }}</pre>
        <a href="{{ route("{$prefix}.printer.reset") }}">reset</a>
        @if (count($jobs) > 0)
            <table class="ui fixed striped table">
                @foreach ($jobs as $job)
                    <tr>
                        <td>{{ $job->title }}</td>
                        <td>{{ $job->state }}</td>
                        <td>{{ \Carbon\Carbon::parse($job->createTimestamp) }}</td>
                    </tr>
                @endforeach
            </table>
        @endif
        @include('printer.partials.chooser-table')
    </div>
@stop
