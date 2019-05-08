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
                            @if ($version->revised_rooms !== null)
                                <div class="d-lg-flex align-items-center">
                                        <a class="btn {{ $loop->first ? 'btn-primary' : 'btn-secondary' }}" href="{{ action('RoominglistExportController@download', $version) }}">Download</a>
                                    <div class="ml-lg-auto mt-2 mt-lg-0 text-muted">
                                        {{ $version->revised_tickets }} Tickets, {{ $version->revised_rooms }} Rooms
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if (count($versions))
                    <button
                        href="{{ action('RoominglistResetController') }}"
                        class="btn btn-sm btn-outline-danger mt-5"
                        onclick="
                            if (confirm('Are you sure you want to remove and reset all versions? This is permanent and cannot be undone!'))
                                { event.preventDefault(); document.getElementById('reset-form').submit(); }
                            else return
                        "
                    >
                        Remove and reset all versions
                    </button>
                @endif
            </div>
        </div>
    </div>

    <form id="export-form" action="{{ action('RoominglistExportController@create') }}" method="POST" style="display:none">
        @csrf
    </form>
    <form id="reset-form" action="{{ action('RoominglistResetController') }}" method="POST" style="display:none">
        @csrf
    </form>
@endsection

@push ('scripts')
    <script>
        Echo.private('App.User.' + {{ request()->user()->id }})
            .notification(({type, version_id}) => {
                if (type == "App\\Notifications\\ExportCompleted") {
                    window.location = `/admin/roominglists/${version_id}/download`;
                }
            })
    </script>
@endpush
