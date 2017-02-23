@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="ui dividing header page-header">
            <h1 class="page-header__title">Registration #{{ $order->id }}</h1>
        </header>

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
                                    <td>{{ money_format('%.2n', $ticket->price) }}</td>
                                @endcan
                                <td>
                                    {{ $ticket->waiver->status }}
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
