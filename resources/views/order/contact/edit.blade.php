@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <h1>Edit Contact</h1>
        </header>

        <div class="ui stackable grid">
            <div class="seven wide column">
                {{ Form::model($contact, ['route' => ['order.contact.update', $order], 'method' => 'PATCH', 'class' => 'ui form']) }}

                    @include('order.contact.partials.form')

                    <button class="ui primary button" type="submit">Update</button>
                    <a href="{{ route('order.show', $order) }}" style="margin-left:1rem">Go Back</a>

                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop

