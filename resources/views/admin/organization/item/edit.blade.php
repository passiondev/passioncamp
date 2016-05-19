@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <h1>Edit Item</h1>
        </header>

        {{ Form::open(['route' => ['admin.organization.item.update', $organization, $item], 'method' => 'PUT', 'class' => 'ui form']) }}

            <div class="field">
                {{ Form::label('item', 'Item') }}
                <p class="form-control-static">{{ $item->item->name }}</p>
            </div>
            <div class="field">
                {{ Form::label('quantity', 'Quantity') }}
                {{ Form::number('quantity', number_format($item->quantity), ['id' => 'quantity']) }}
            </div>
            <div class="field">
                {{ Form::label('cost', 'Cost/ea') }}
                {{ Form::text('cost', $item->cost, ['id' => 'cost']) }}
            </div>

            <button class="ui primary button">Update</button>

        {{ Form::close() }}

    </div>
@stop
