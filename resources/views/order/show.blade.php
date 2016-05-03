@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1 class="page-header__title">Registration #{{ $order->id }}</h1>
        </header>

        <section>
            <header class="section__header">
                <h4>Attendees</h4>
                <div class="section-header__actions">
                    <a class="button small" href="{{ route('order.ticket.create', $order) }}">Add Attendee</a>
                    @can ('record-transactions', $order->organization)
                        <a href="{{ route('order.transaction.create', $order) }}" class="button small">Record Transacation</a>
                    @endcan
                </div>
            </header>
            @if ($order->tickets->count() > 0)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th></th>
                            @can ('record-transactions', $order->organization)
                                <th>Ticket Price</th>
                            @endcan
                            <th>Camp Waiver</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->tickets as $ticket)
                            <tr class="{{ $ticket->is_canceled ? 'canceled' : '' }}">
                                <th>{{ $ticket->name }}</th>
                                <td>
                                    @include('ticket/partials/label')
                                </td>
                                @can ('record-transactions', $order->organization)
                                    <td>@currency($ticket->price)</td>
                                @endcan
                                <td>
                                    @unless ($ticket->is_canceled)
                                        @unless ($ticket->waiver)
                                            <Waiver inline-template>
                                                <a v-on:click.prevent="send" href="{{ route('ticket.waiver.create', $ticket) }}">send waiver</a>
                                            </Waiver>
                                        @else
                                            {{ $ticket->waiver->status }}
                                            <Waiver inline-template>
                                                <a href="{{ route('ticket.waiver.reminder', $ticket) }}">send reminder</a>
                                            </Waiver>
                                        @endif
                                    @endunless
                                </td>
                                <td>
                                    @can ('edit', $ticket)
                                        <a style="text-decoration:none!important" href="{{ route('ticket.edit', $ticket) }}">edit</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="callout secondary" style="margin-top: 1rem;text-align:center">
                    <a class="button" href="{{ route('order.ticket.create', $order) }}">Add Attendee</a>
                </div>
            @endif
        </section>
        <div class="row">
            <div class="large-4 columns">
                <h4>Contact</h4>
                <dl>
                    <dt>{{ $order->user->person->name }}</dt>
                    <dd>{{ $order->user->person->phone }}</dd>
                    <dd>{{ $order->user->person->email }}</dd>
                </dl>
                <a href="{{ route('order.contact.edit', $order) }}" class="xsmall outline button">edit</a>
            </div>
            @can ('record-transactions', $order->organization)
                <div class="large-7 columns">
                    @include('order/partials/registration_summary')
                </div>
            @endcan
        </div>
        @if (auth()->user()->is_super_admin)
            <section class="panel panel-default" id="notes">
                <header class="panel-heading">
                    <h4>Notes</h4>
                </header>
                <div class="panel-body">
                    @foreach ($order->notes as $note)
                        <div class="note">
                            <p class="note__body">
                                {!! nl2br($note->body) !!}
                            </p>
                            <h6 class="note__author">&mdash; {{ $note->author ? $note->author->email :'' }} <span title="{{ $note->created_at->toDayDateTimeString() }}">{{ $note->created_at->toDayDateTimeString() }}</span></h6>
                        </div>
                    @endforeach
                    {!! Form::open(['route' => ['order.note.store', $order]]) !!}
                        <div class="form-group">
                            {!! Form::textarea('body', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary">Add Note</button>
                        </div>
                    {!! Form::close() !!}
                </div>
            </section>
        @endif
    </div>
@stop
