@extends('layouts.roominglist')

@section('content')
    <roominglists

        :initial-tickets="{{ json_encode($tickets) }}"
        inline-template
    >
        <div class="row d-flex align-items-stretch h-100">
            <div class="rooms overflowing col-9 d-flex flex-column h-100">
                <h1>Rooms</h1>
                <div id="rooms-scroll" style="overflow-y: scroll; overflow-x: hidden">
                    <div class="alert alert-info">
                        <h3>Important Info About Creating Your Rooming List</h3>
                        <p>Each room accounts for sleeping space for 4 people. All rooms will be one of the following bed types: two beds (double or queen) or a queen/ king bed with a sleeper sofa.</p>
                        <p>We will do our best to ensure that all groups are assigned to rooms on the same floor or as close together as possible.</p>
                    </div>
                    <div class="row">
                        @foreach ($rooms as $room)
                            <div class="col-xl-4 col-6 mb-3">
                                <room :room="{{ json_encode($room) }}" :tickets="getTicketsForRoom({{ $room->id }})">
                                    @if (Auth::user()->isSuperAdmin())
                                        <template slot="organization">
                                            <small class="text-muted">{{ $room->organization->church->name }}</small><br>
                                        </template>
                                    @endif
                                    <div slot="actions">
                                        <a href="{{ action('RoomController@edit', $room) }}" class="btn btn-outline-secondary btn-sm">edit</a>
                                        @if (Auth::user()->isSuperAdmin())
                                            {{-- <span><a href="{{ route('roominglist.label', $room) }}" {!! session('printer') == 'PDF' ? 'target="_blank"' : '' !!} {!! session('printer') && session('printer') != 'PDF' ? 'data-test="test" v-on:click.prevent="ajax"' : '' !!}>print</a></span> --}}
                                        @endif
                                    </div>
                                </room>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <unassigned :tickets="unassigned"></unassigned>
        </div>
    </roominglists>
@stop
