@extends('layouts.semantic')

@section('content')
    <div class="ui container">

        @if (session('success'))
            <div class="ui success message">
                {{ session('success') }}
            </div>
        @endif


        <roominglist-overview inline-template :organization="{{ request('organization') }}">
        <div class="ui top attached segment">
            <select class="ui dropdown" data-context="table tbody tr" v-model="organization">
                <option value=""></option>
                @foreach ($organizations as $organization)
                    <option value="{{ $organization->id }}">{{ $organization->church->name }} - {{ $organization->church->location }}</option>
                @endforeach
            </select>
            <a class="ui small button" v-show="organization && organization > 0" href="/admin/organization/@{{ organization }}/rooms/checkin">check in rooms</a>
            <a class="ui small button" v-show="organization && organization > 0" href="/admin/organization/@{{ organization }}/rooms/print">print all</a>
        </div>
        <table class="ui attached striped table" id="rooms">
            <thead>
                <tr>
                    <th class="four wide">Church</th>
                    <th class="two wide">Hotel</th>
                    <th class="two wide">Name</th>
                    <th class="three wide">People</th>
                    <th class="" style="text-align:center">Capacity</th>
                    <th class="" style="text-align:center">Assigned</th>
                    <th class=""></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rooms as $room)
                    <tr data-organization="{{ $room->organization->id }}" class="{{ $room->capacity == $room->tickets->count() ? 'positive' : '' }} {{ $room->tickets->count() == 0 ? 'negative' : '' }}">
                        <td>
                            <h5 class="ui header">
                                {{ link_to_route('admin.organization.show', $room->organization->church->name, $room->organization) }}
                                <div class="sub header">
                                    {{ $room->organization->church->location }}
                                </div>
                            </h5>
                        </td>
                        <td>
                            {{ $room->hotel->name }}
                            @if ($room->roomnumber)
                                #{{ $room->roomnumber }}
                            @endif
                        </td>
                        <td>{{ $room->name }}</td>
                        <td style="font-size:85%">
                            @foreach ($room->tickets->assigendSort() as $ticket)
                                {{ $ticket->person->name }}<br>
                            @endforeach
                        </td>
                        <td style="text-align:center">{{ $room->capacity }}</td>
                        <td style="text-align:center">{{ $room->tickets->count() }}</td>
                        <td>
                            @if ($room->is_key_received)
                                <i class="checkmark green icon"></i> key
                            @else
                                <a v-on:click.prevent="ajax" href="{{ route('roominglist.keyReceived', $room) }}">key received</a>
                            @endif

                            <br>

                            @if ($room->is_checked_in)
                                <i class="checkmark green icon"></i> checked in
                            @else
                                <a v-on:click.prevent="ajax" href="{{ route('roominglist.checkin', $room) }}">check in</a>
                            @endif
                            
                            <br>

                            <a href="{{ route('roominglist.edit', $room) }}">edit</a>
                            
                            <br>

                            <a href="{{ route('roominglist.label', $room) }}" {!! session('printer') == 'PDF' ? 'target="_blank"' : '' !!} {!! session('printer') && session('printer') != 'PDF' ? 'data-test="test" v-on:click.prevent="ajax"' : '' !!}>print</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </roominglist-overview>
    </div>
@stop
