@extends('layouts.bootstrap4')

@section('content')
    <div class="container">
        @if (session('loading'))
            <div class="alert alert-info">
                <p>{{ session('loading') }}</p>
            </div>
        @endif
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <header class="card-header d-flex align-items-center justify-content-between">
                        Export Versions
                        <a href="{{ action('RoominglistExportController@create') }}" class="btn btn-sm btn-outline-primary" onclick="event.preventDefault(); document.getElementById('export-form').submit();">New Version</a>
                    </header>
                    @foreach ($versions->reverse() as $version)
                        <div class="card-block dividing">
                            <h4 class="card-title">Version #{{ $version->id }}</h4>
                            <h6 class="card-subtitle mb-3 text-muted">{{ $version->created_at->diffForHumans() }}</h6>
                            <div class="d-lg-flex align-items-center">
                                @if ($version->file_path)
                                    <a class="btn {{ $loop->first ? 'btn-primary' : 'btn-secondary' }}" href="{{ action('RoominglistExportController@download', $version) }}">Download</a>
                                @endif
                                <div class="ml-lg-auto mt-2 mt-lg-0 text-muted">
                                    {{ $version->revised_tickets }} Tickets, {{ $version->revised_rooms }} Rooms
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <form id="export-form" action="{{ action('RoominglistExportController@create') }}" method="POST" style="display:none">
        {{ csrf_field() }}
    </form>
@endsection

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
