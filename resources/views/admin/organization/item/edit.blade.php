@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <h1>Edit Item</h1>
        </header>

        {{ Form::model($item, ['route' => ['admin.organization.item.update', $organization, $item], 'method' => 'PUT', 'class' => 'ui form']) }}

            <div class="field">
                {{ Form::label('item', 'Item') }}
                {{ Form::select('item_id', $items, null, ['id' => 'item', 'class' => 'ui dropdown']) }}
            </div>
            <div class="field">
                {{ Form::label('quantity', 'Quantity') }}
                {{ Form::number('quantity', null, ['id' => 'quantity']) }}
            </div>
            <div class="field">
                {{ Form::label('cost', 'Cost/ea') }}
                {{ Form::text('cost', null, ['id' => 'cost']) }}
            </div>

            <button class="ui primary button">Update</button>

        {{ Form::close() }}

    </div>
@stop
