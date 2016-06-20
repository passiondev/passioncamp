@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <h1 class="ui dividing header">Rooming List Export</h1>
        @if (session('loading'))
            <div class="ui icon success message">
                <i class="notched circle loading icon"></i>
                <div class="content">
                    <p>{{ session('loading') }}</p>
                </div>
            </div>
        @endif
        <div class="ui equal width stackable grid">
            <div class="column">
                {{ Form::open(['route' => 'roominglist.export.version', 'class' => 'ui form']) }}
                    <div class="ui card" style="margin-top:0">
                        <div class="content">
                            <h1 class="header">Export Versions</h1>
                        </div>
                        <div class="extra content">
                            <button type="submit" class="ui primary button">Save Current Version and Generate Export</button>
                        </div>
                        <div class="content">
                            <div class="ui feed">
                                @unless ($versions->count())
                                    <p><i>No Versions Created Yet</i></p>
                                @endunless
                                @foreach ($versions->reverse() as $version)
                                    <div class="event">
                                        <div class="content">
                                            <div class="summary">
                                                Version #{{ $version->id }}
                                                <div class="date">
                                                    {{ $version->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                            <a href="{{ route('roominglist.export.download', $version) }}">Download</a><br>
                                            <div class="meta">
                                                {{ $version->revised_tickets }} Tickets, {{ $version->revised_rooms }} Rooms
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop

@push ('scripts')
    <script src="https://js.pusher.com/3.1/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher('{{ config('pusher.connections.main.auth_key') }}', {
            encrypted: true
        });

        var channel = pusher.subscribe('roominglist.export');
        channel.bind('generated', function(data) {
            window.location = '{{ url('/roominglist/export') }}/' + data.version + '/download';
        });
    </script>
@endpush