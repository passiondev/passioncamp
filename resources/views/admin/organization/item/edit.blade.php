@extends('layouts.bootstrap4')

@section('content')
    <div class="container-fluid">
        <header class="page-header">
            <h1>Edit Item</h1>
        </header>

        <form action="{{ action('OrganizationItemController@update', [$organization, $item]) }}" method="POST">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}

            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity', $item->quantity) }}">
            </div>
            <div class="form-group">
                <label for="cost">Cost/ea</label>
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="number" name="cost" id="cost" class="form-control" value="{{ old('cost', $item->cost / 100) }}">
                    <span class="input-group-addon">.00</span>
                </div>
            </div>


            <button class="btn btn-primary">Update Item</button>

        </form>

    </div>
@stop
