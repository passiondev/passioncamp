@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        @if (session('uncheck'))
            <div class="ui yellow message" style="display:flex;justify-content:space-between;align-items:center">
                {{ session('ticket_name') }} has been un-checked in.
            </div>
        @endif
        @if (session('ticket_id'))
            <div class="ui success message" style="display:flex;justify-content:space-between;align-items:center">
                {{ session('ticket_name') }} has been checked in.
                <form action="/checkin/{{ session('ticket_id') }}/undo" method="POST">
                    {{ csrf_field() }}
                    <button type="submit" class="ui green basic button">Undo?</button>
                </form>
            </div>
        @endif

        .
        <div class="ui top attached header" style="display:flex;justify-content:space-between;align-items:center">
            <h2 style="flex:1">Check In</h2>
            <div style="flex:1;margin-left:1em;margin-right:1em" class="ui purple progress" data-value="{{ auth()->user()->organization->attendees->active()->ofAgegroup('student')->checkedIn()->count() }}" data-total="{{ auth()->user()->organization->attendees->active()->ofAgegroup('student')->count() }}">
                <div class="bar">
                    <div class="progress"></div>
                </div>
                <div class="label">{{ auth()->user()->organization->attendees->active()->ofAgegroup('student')->checkedIn()->count() }} of {{ auth()->user()->organization->attendees->active()->ofAgegroup('student')->count() }} Students</div>
            </div>
            <div style="flex:1;margin-left:1em;margin-right:1em" class="ui teal progress" data-value="{{ auth()->user()->organization->attendees->active()->ofAgegroup('leader')->checkedIn()->count() }}" data-total="{{ auth()->user()->organization->attendees->active()->ofAgegroup('leader')->count() }}">
                <div class="bar">
                    <div class="progress"></div>
                </div>
                <div class="label">{{ auth()->user()->organization->attendees->active()->ofAgegroup('leader')->checkedIn()->count() }} of {{ auth()->user()->organization->attendees->active()->ofAgegroup('leader')->count() }} Leaders</div>
            </div>
        </div>

        <div class="ui attached segment">
            <form action="/checkin" method="GET" class="ui form">
                <div class="ui big fluid action input">
                    <input autofocus type="search" name="search" class="form-control input-group-field" placeholder="Search..." value="{{ request('search') }}">
                    <button class="ui icon button" type="submit"><i class="search icon"></i></button>
                </div>
            </form>
        </div>

        <table class="ui attached striped fixed table">
            @foreach ($tickets as $ticket)
                <tr>
                    <td>
                        <h4 class="ui header">
                            <a href="{{ route('ticket.edit', $ticket) }}">{{ $ticket->person->name }}</a>
                        </h4>
                    </td>
                    <td>
                        @include('ticket/partials/label')
                    </td>
                    <td>
                        {{ $ticket->squad }}
                    </td>
                    <td class="ui list">
                        {!! $ticket->waiver && $ticket->waiver->is_complete ? '' : '<div class="item"><i class="red warning sign icon"></i>Camp Waiver</div>' !!}
                        {!! $ticket->has_pcc_waiver ? '' : '<div class="item"><i class="red warning sign icon"></i>PCC Waiver</div>' !!}
                        {!! $ticket->order->balance == 0 ? '' : '<div class="item"><i class="red warning sign icon"></i>Balance Due</div>' !!}
                    </td>
                    <td class="right aligned">
                        @unless ($ticket->is_checked_in)
                            <form action="/checkin/{{ $ticket->id }}" method="POST">
                                {{ csrf_field() }}
                                <button type="submit" class="ui primary button">Check In</button>
                            </form>
                        @else
                            <i class="large green check icon"></i> {{ $ticket->checked_in_at->diffForHumans() }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@stop

@push ('scripts')
<script>
    $(document).ready(function(){
        $('.ui.progress').progress();
    });
</script>
@endpush