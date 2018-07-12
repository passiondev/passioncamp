@extends('layouts.bootstrap4')

@section('content')
    <div class="ui container">
        <header class="page-header">
            <h1>Add Church</h1>
        </header>

        <form action="{{ route('admin.organizations.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="church__name">Name</label>
                <input type="text" name="name" id="church__name" value="{{ old('name') }}" class="form-control">
            </div>

            <button class="btn btn-primary">Create Church</button>
        </form>
    </div>
@stop
