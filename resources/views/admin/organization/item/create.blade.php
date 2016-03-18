@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Add Item</h1>
        </header>

        {{ Form::open(['route' => ['admin.organization.item.store', $organization]]) }}

            <div class="form-group">
                {{ Form::label('item', 'Item', ['class' => 'control-label']) }}
                {{ Form::select('item', $items, null, ['id' => 'item', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('quantity', 'Quantity', ['class' => 'control-label']) }}
                {{ Form::number('quantity', null, ['id' => 'quantity', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('cost', 'Cost/ea', ['class' => 'control-label']) }}
                {{ Form::text('cost', null, ['id' => 'cost', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                <button class="btn btn-primary">Submit</button>
            </div>

        {{ Form::close() }}

    </div>
@stop
