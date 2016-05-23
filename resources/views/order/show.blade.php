@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="ui dividing header page-header">
            <h1 class="page-header__title">Registration #{{ $order->id }}</h1>
        </header>

        @unless ($order->hasContactInfo())
            <div class="callout warning" style="margin-bottom:2rem">
                <p>Please add a point of contact for this registration.</p>
                <p><a href="{{ route('order.contact.create', $order) }}" class="button outline small">Add Contact</a></p>
            </div>
        @endif

        <section>
            <header class="ui dividing header section__header">
                <h3>Attendees</h3>
                <div class="sub header section-header__actions">
                    <a class="button small" href="{{ route('order.ticket.create', $order) }}">Add Attendee</a>
                    @can ('record-transactions', $order->organization)
                        <a href="{{ route('order.transaction.create', $order) }}" class="button small">Record Transacation</a>
                    @endcan
                </div>
            </header>
            @if ($order->tickets->count() > 0)
                <table class="ui very basic striped table">
                    <thead class="mobile hidden">
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
                                <td>{{ $ticket->name }}</td>
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
                                        @elseif ($ticket->waiver->status == 'signed')
                                            {{ $ticket->waiver->status }}
                                        @else
                                            {{ $ticket->waiver->status }}
                                            @unless ($ticket->waiver->status == 'signed')
                                                <Waiver inline-template>
                                                    <a href="{{ route('ticket.waiver.reminder', $ticket) }}">send reminder</a>
                                                </Waiver>
                                                @if (Auth::user()->is_super_admin)
                                                    <a href="{{ route('ticket.waiver.cancel', $ticket) }}">cancel</a>
                                                @endif
                                            @endif
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

        <div class="ui divider"></div>

        <div class="ui grid">
            <div class="six wide column">
                <h4>Contact</h4>
                @if ($order->hasContactInfo())
                    <dl>
                        <dt>{{ $order->user->person->name }}</dt>
                        <dd>{{ $order->user->person->phone }}</dd>
                        <dd>{{ $order->user->person->email }}</dd>
                    </dl>
                    <a href="{{ route('order.contact.edit', $order) }}" class="ui mini basic blue button">edit</a>
                @else
                    <a href="{{ route('order.contact.create', $order) }}" class="ui mini basic blue button">add contact</a>
                @endif
            </div>
            @can ('record-transactions', $order->organization)
                <div class="ten wide column">
                    @include('order/partials/registration_summary')
                </div>
            @endcan
        </div>
        @if (auth()->user()->is_super_admin)
            <section class="ui segment panel panel-default" id="notes">
                <header class="ui dividing header panel-heading">
                    <h4>Notes</h4>
                </header>
                <div class="ui comments">
                    @foreach ($order->notes as $note)
                        <div class="comment">
                            <div class="content">
                                <span class="author">{{ $note->author ? $note->author->email :'' }}</span>
                                <div class="metadata">
                                    <span class="date">{{ $note->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="text">
                                    {!! nl2br($note->body) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {!! Form::open(['route' => ['order.note.store', $order], 'class' => 'ui form']) !!}
                    <div class="field">
                        {!! Form::textarea('body', null, ['rows' => '3']) !!}
                    </div>
                    <button class="ui small primary button">Add Note</button>
                {!! Form::close() !!}
            </section>
        @endif
    </div>
@stop
