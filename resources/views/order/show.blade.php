@extends('layouts.bootstrap4')

@section('content')
    <div class="container">
        <header class="d-md-flex justify-content-between align-items-center mb-3">
            <h1 class="page-header__title">Registration #{{ $order->id }}</h1>
            <div>
                @can ('update', $order)
                    <a class="btn btn-secondary" href="{{ action('OrderTicketController@create', $order) }}">Add Attendee</a>
                    <a class="btn btn-secondary" href="{{ action('OrderTransactionController@create', $order) }}">Add Transaction</a>
                @endcan
            </div>
        </header>

        @unless ($order->hasContactInfo())
            <div class="ui warning message" style="margin-bottom:2rem">
                <p>Please add a point of contact for this registration.</p>
                {{-- <p><a href="{{ route('order.contact.create', $order) }}" class="button outline small">Add Contact</a></p> --}}
            </div>
        @endif

        <section class="card mb-5">
            <header class="card-header">
                <h3>Attendees</h3>
            </header>

            <table class="table table-responsive">
                <thead class="mobile hidden">
                    <tr>
                        <th>Name</th>
                        <th></th>
                        <th>Ticket Price</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->tickets as $ticket)
                        <tr class="{{ $ticket->is_canceled ? 'canceled' : '' }}">
                            <td>{{ $ticket->name }}</td>
                            <td>
                                @include('ticket/partials/label')
                            </td>
                            <td>{{ money_format('%.2n', $ticket->price / 100) }}</td>
                            <td>
                                @can ('update', $ticket)
                                    <a class="btn btn-outline-secondary btn-sm" href="{{ action('TicketController@edit', $ticket) }}">edit</a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <div class="ui divider"></div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-3">
                    <header class="card-header d-flex justify-content-between">
                        <h4>Contact</h4>
                        <div>
                            @can ('update', $order->user->person)
                                <a href="{{ action('PersonController@edit', $order->user->person) }}" class="btn btn-outline-secondary btn-sm">edit</a>
                            @endcan
                        </div>
                    </header>
                    <div class="card-block">
                        <dl>
                            <dt>{{ $order->user->person->name }}</dt>
                            <dd>{{ $order->user->person->phone }}</dd>
                            <dd>{{ $order->user->person->email }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            @if ($order->organization->can_record_transactions)
                <div class="col-lg-8">
                    @include('order/partials/registration_summary')
                </div>
            @endif
        </div>

        <table class="table table-responsive table-striped align-middle">
            <tr>
                <td>{!! $order->user->email or '<i style="font-size:85%;font-weight:normal">none</i>' !!}</td>
                <td>{{ $order->user->person->name or '' }}</td>
                <td style="line-height: 1">
                    @if (! $order->user->is_registered)
                        <input type="text" style="margin-bottom:0" readonly value="{{ route('complete.registration', [$order->user, $order->user->hash]) }}">
                    @elseif ($order->user->access == 100)
                        PASSION CAMP ADMIN
                    @else
                        {{ $order->user->organization->church->name }}<br>
                        <small><em>{{ $order->user->organization->church->location }}</em></small>
                    @endif
                </td>
                @can ('impersonate', $order->user)
                    <td>
                        <a href="{{ action('Auth\ImpersonationController@impersonate', $order->user) }}" class="btn btn-sm btn-outline-secondary">impersonate</a>
                    </td>
                @endif
            </tr>
        </table>

        @can ('record-notes', $order)
            <section class="ui segment panel panel-default" id="notes">
                <header class="ui dividing header panel-heading">
                    <h4>Notes</h4>
                </header>
                <div class="ui comments">
                    @foreach ($order->notes as $note)
                        <blockquote class="blockquote">
                            <p class="mb-0">{!! nl2br($note->body) !!}</p>
                            <footer class="blockquote-footer">{{ $note->author ? $note->author->email :'' }}, <i>{{ $note->created_at->diffForHumans() }}</i></footer>
                        </blockquote>
                    @endforeach
                </div>
                <form action="{{ action('OrderNoteController@store', $order) }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <textarea name="body" cols="30" rows="4" class="form-control"></textarea>
                    </div>
                    <button class="btn btn-secondary">Add Note</button>
                </form>
            </section>
        @endif
    </div>
@stop
