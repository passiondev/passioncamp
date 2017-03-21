@extends('layouts.bootstrap4')

@section('content')
    <div class="container">
        <header>
            <h1>Add Item</h1>
        </header>

        <form action="{{ action('Super\OrganizationItemController@store', $organization) }}" method="POST">
            {{ csrf_field() }}

            <div class="form-group">
                <label for="item">Item</label>
                <select name="item" id="item" class="form-control">
                    @foreach ($items as $item)
                        <option></option>
                        <option value="{{ $item->id }}" @if (old('item') == $item->id) selected @endif>{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') }}">
            </div>
            <div class="form-group">
                <label for="cost">Cost/ea</label>
                <input type="number" name="cost" id="cost" class="form-control" value="{{ old('cost') }}">
            </div>

            <button class="btn btn-primary">Submit</button>

        </form>
    </div>
@endsection
