@extends('layouts.bootstrap4')

@section('content')
    <div class="container mt-3">
        @if (session()->has('checked_in'))
            <div class="alert alert-success d-flex align-items-center justify-content-between">
                <span>{{ session('checked_in.name') }} checked in!</span>
                <a href="{{ action('CheckinController@create', session('checked_in.id')) }}"
                    class="btn btn-outline-success"
                    onclick="event.preventDefault(); document.getElementById('uncheckin-{{ session('checked_in.id') }}-form').submit()">Undo check in?</a>
            </div>
            <form action="{{ action('CheckinController@create', session('checked_in.id')) }}" method="POST" id="uncheckin-{{ session('checked_in.id') }}-form">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
            </form>
        @endif

        @if (session()->has('unchecked_in'))
            <div class="alert alert-warning">{{ session('unchecked_in.name') }} un-checked.</div>
        @endif

        <div class="card mb-5">
            <header class="card-header d-flex align-items-center justify-content-between">
                <h1>Check In</h1>
                <div class="text-right">
                    <strong class="color-student">Students</strong> {{ 100 * $students_progress }}% <small>({{ $students_remaining }} remaining)</small><br>
                    <strong class="color-leader">Leaders</strong> {{ 100* $leaders_progress }}% <small>({{ $leaders_remaining }} remaining)</small>
                </div>
            </header>
                <div class="progress" style="border-radius: 0;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" style="height:3px;width:{{ 100 * $students_progress * $students_percentage }}%"></div>
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="height:3px;width:{{ 100* $leaders_progress * $leaders_percentage }}%"></div>
                </div>
            @unless(session('printer'))
                <div class="card-block">
                    <a href="{{ route('printers.index') }}">Select a printer...</a>
                </div>
            @else
                <div class="card-block">
                    <form action="{{ action('CheckinController@index') }}" method="GET" class="d-flex">
                        <input name="search" type="search" class="form-control form-control-lg mr-2" autofocus autocomplete="off" placeholder="Search..." value="{{ request('search') }}" onfocus="this.value = this.value">
                        <button type="submit" class="btn btn-lg btn-secondary">
                            <span style="display:inline-block;transform: rotate(-45deg)">&#9906;</span>
                        </button>
                    </form>
                </div>
                @if (starts_with(request()->query('search'), 'leader'))
                    <div class="card-footer text-center">
                        <a href="{{ action('CheckinController@allLeaders') }}" class="btn btn-primary">Check In All Leaders</a>
                    </div>
                @elseif ($tickets)
                    <table class="table table-striped mb-0"  style="vertical-align: middle; table-layout: fixed">
                        @foreach ($tickets as $ticket)
                            <tr>
                                <td style="vertical-align: middle">
                                    <a href="{{ action('TicketController@edit', $ticket) }}">
                                        {{ $ticket->name }}
                                    </a>
                                </td>
                                <td style="vertical-align: middle">
                                    @include('ticket/partials/label')
                                </td>
                                <td style="vertical-align: middle">
                                    <ul class="list-unstyled mb-0">
                                        @unless ($ticket->waiver && $ticket->waiver->isComplete())
                                            <li class="text-danger">@icon('exclamation-outline') Camp Waiver</li>
                                        @endunless
                                        @unless ($ticket->pcc_waiver)
                                            <li class="text-danger">@icon('exclamation-outline') PCC Waiver</li>
                                        @endunless
                                        @if ($ticket->order->user->balance > 0)
                                            <li class="text-danger">@icon('exclamation-outline') Balance Due</li>
                                        @endif
                                    </ul>
                                </td>
                                <td class="text-center" style="vertical-align: middle">
                                    @unless ($ticket->is_checked_in)
                                        <a href="{{ action('TicketWristbandsController@signedShow', $ticket->toRouteSignatureArray()) }}"
                                            class="btn btn-outline-primary"
                                            onclick="event.preventDefault(); document.getElementById('checkin-{{ $ticket->id }}-form').submit()">Check In</a>

                                        <form action="{{ action('CheckinController@create', $ticket) }}" method="POST" id="checkin-{{ $ticket->id }}-form">
                                            {{ csrf_field() }}
                                        </form>
                                    @else
                                        <a href="{{ action('CheckinController@create', $ticket) }}"
                                            class="btn btn-sm btn-outline-secondary"
                                            onclick="event.preventDefault(); document.getElementById('uncheckin-{{ $ticket->id }}-form').submit()">Un-Check</a>

                                        <form action="{{ action('CheckinController@create', $ticket) }}" method="POST" id="uncheckin-{{ $ticket->id }}-form">
                                            {{ method_field('DELETE') }}
                                            {{ csrf_field() }}
                                        </form>
                                    @endunless
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            @endunless
        </div>
    </div>
@stop
