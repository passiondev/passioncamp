@extends('layouts.bootstrap4')

@section('content')
    <div class="container-fluid">
        <header class="page-header">
            <h1>Edit Item</h1>
        </header>

        <form action="{{ action('OrganizationItemController@update', [$organization, $item]) }}" method="POST">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}

            <h4>{{ $item->name }}</h4>

            <div class="form-group">
                <label for="notes">Notes</label>
                <input type="text" name="notes" id="notes" class="form-control" value="{{ old('notes', $item->notes) }}">
            </div>

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


            <div class="d-flex justify-content-between align-items-center">
                <button class="btn btn-primary">Update Item</button>
                <button
                    class="btn btn-sm btn-outline-danger"
                    onclick="event.preventDefault(); if (confirm('Remove item?')) document.getElementById('delete-item-form').submit(); else return;"
                >
                    Delete
                </button>
            </div>
        </form>

        <form action="{{ route('admin.organizations.items.destroy', [$organization, $item]) }}" method="POST" id="delete-item-form">
            @method('DELETE')
            @csrf
        </form>
    </div>
@stop
