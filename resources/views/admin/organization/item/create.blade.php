@extends('layouts.bootstrap4')

@section('content')
    <div class="container-fluid">
        <header>
            <h1>Add Item</h1>
        </header>

        <form action="{{ action('OrganizationItemController@store', $organization) }}" method="POST">
            {{ csrf_field() }}

            <div class="form-group">
                <label for="item">Item</label>
                <select name="item" id="item" class="form-control">
                    <option></option>
                    @foreach ($items as $type => $group)
                        <optgroup label={{ ucfirst($type) }}>
                            @foreach ($group as $item)
                                <option value="{{ $item->id }}" @if (old('item') == $item->id) selected @endif>{{ $item->name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="notes">Notes</label>
                <input type="text" name="notes" id="notes" class="form-control" value="{{ old('notes') }}">
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') }}">
            </div>
            <div class="form-group">
                <label for="cost">Cost/ea</label>
                <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="number" name="cost" id="cost" class="form-control" value="{{ old('cost') }}">
                    <span class="input-group-addon">.00</span>
                </div>
            </div>

            <button class="btn btn-primary">Submit</button>

        </form>
    </div>
@endsection
