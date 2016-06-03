@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="ui dividing header page-header">
            <h1 class="page-header__title">Registration #{{ $order->id }}</h1>
            @if (Auth::user()->isSuperAdmin())
                <h2>{{ $order->organization->church->name }}</h2>
            @endif
        </header>

        <div class="ui secondary menu">
            @can ('add-attendees', $order)
                <div class="item">
                    <a class="ui primary button" href="{{ route('order.ticket.create', $order) }}">Add Attendee</a>
                </div>
            @endcan
            @can ('record-transactions', $order->organization)
                <div class="item">
                    <a class="ui primary button" href="{{ route('order.transaction.create', $order) }}" class="button small">Record Transacation</a>
                </div>
            @endcan
            @if (Auth::user()->isOrderOwner())
                <div class="item">
                    <a class="ui primary button" href="{{ route('order.payment.create', $order) }}" class="button small">Make Payment</a>
                </div>
            @endif
        </div>

        @unless ($order->hasContactInfo())
            <div class="callout warning" style="margin-bottom:2rem">
                <p>Please add a point of contact for this registration.</p>
                <p><a href="{{ route('order.contact.create', $order) }}" class="button outline small">Add Contact</a></p>
            </div>
        @endif

        <section>
            <header class="ui dividing header section__header">
                <h3>Attendees</h3>
            </header>
            @if ($order->tickets->count() > 0)
                <table class="ui very basic striped table">
                    <thead class="mobile hidden">
                        <tr>
                            <th>Name</th>
                            <th></th>
                            @if ($order->organization->can_record_transactions)
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
                                @if ($order->organization->can_record_transactions)
                                    <td>{{ money_format('$%.2n', $ticket->price) }}</td>
                                @endcan
                                <td>
                                    @unless ($ticket->is_canceled)
                                        @if ($ticket->waiver && auth()->user()->isOrderOwner())
                                            {{ $ticket->waiver->status }}
                                        @elseif ($ticket->waiver)
                                            {{ $ticket->waiver->status }}
                                            @unless ($ticket->waiver->status == 'signed')
                                                <Waiver inline-template>
                                                    <a href="{{ route('ticket.waiver.reminder', $ticket) }}">send reminder</a>
                                                </Waiver>
                                                @if (Auth::user()->isSuperAdmin())
                                                    <a href="{{ route('ticket.waiver.cancel', $ticket) }}">cancel</a>
                                                @endif
                                            @endif
                                        @else
                                            <Waiver inline-template>
                                                <a v-on:click.prevent="send" href="{{ route('ticket.waiver.create', $ticket) }}">send waiver</a>
                                            </Waiver>
                                        @endif
                                    @endunless
                                </td>
                                <td>
                                    @can ('edit', $ticket)
                                        <a class="ui mini basic blue button" style="text-decoration:none!important" href="{{ route('ticket.edit', $ticket) }}">edit</a>
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

        <div class="ui stackable grid">
            <div class="six wide column">
                <h4>Contact</h4>
                @if ($order->hasContactInfo())
                    <dl>
                        <dt>{{ $order->user->person->name }}</dt>
                        <dd>{{ $order->user->person->phone }}</dd>
                        <dd>{{ $order->user->person->email }}</dd>
                    </dl>
                    <p><a href="{{ route('order.contact.edit', $order) }}" class="ui mini basic blue button">edit</a></p>
                @else
                    @can('edit-contact', $order)
                        <p><a href="{{ route('order.contact.create', $order) }}" class="ui mini basic blue button">add contact</a></p>
                    @endcan
                @endif
            </div>
            @if ($order->organization->can_record_transactions)
                <div class="ten wide column">
                    @include('order/partials/registration_summary')
                </div>
            @endif
        </div>
        @if (auth()->user()->isSuperAdmin())
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
