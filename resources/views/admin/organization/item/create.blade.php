@extends('layouts.semantic')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <h1>Add Item</h1>
        </header>

        {{ Form::open(['route' => ['admin.organization.item.store', $organization], 'class' => 'ui form']) }}

            <div class="field">
                {{ Form::label('item', 'Item') }}
                {{ Form::select('item', $items, null, ['id' => 'item', 'class' => 'ui dropdown']) }}
            </div>
            <div class="field">
                {{ Form::label('quantity', 'Quantity') }}
                {{ Form::number('quantity', null, ['id' => 'quantity']) }}
            </div>
            <div class="field">
                {{ Form::label('cost', 'Cost/ea') }}
                {{ Form::text('cost', null, ['id' => 'cost']) }}
            </div>

            <button class="ui primary button">Submit</button>

        {{ Form::close() }}

    </div>
@stop
