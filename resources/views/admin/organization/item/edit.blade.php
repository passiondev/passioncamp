@extends('layouts.app')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Edit Item</h1>
        </header>

        {{ Form::open(['route' => ['admin.organization.item.update', $organization, $item], 'method' => 'PUT']) }}

            <div class="form-group">
                {{ Form::label('item', 'Item', ['class' => 'control-label']) }}
                <p class="form-control-static">{{ $item->item->name }}</p>
            </div>
            <div class="form-group">
                {{ Form::label('quantity', 'Quantity', ['class' => 'control-label']) }}
                {{ Form::number('quantity', number_format($item->quantity), ['id' => 'quantity', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('cost', 'Cost/ea', ['class' => 'control-label']) }}
                {{ Form::text('cost', $item->cost, ['id' => 'cost', 'class' => 'form-control']) }}
            </div>
            <div class="form-group">
                <button class="btn btn-primary">Update</button>
            </div>

        {{ Form::close() }}

    </div>
@stop
