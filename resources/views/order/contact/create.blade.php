@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Add Contact</h1>
        </header>

        <div class="row">
            <div class="large-5 columns">
                {{ Form::open(['route' => ['order.contact.store', $order]]) }}

                    @include('order.contact.partials.form')
                    
                    <div class="form-group form-actions">
                        <button type="submit">Create</button>
                        <a href="{{ route('order.show', $order) }}" style="margin-left:1rem">Go Back</a>
                    </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop

