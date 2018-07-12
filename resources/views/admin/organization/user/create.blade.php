@extends('layouts.bootstrap4')

@section('content')
    <div class="container">
        <header class="page-header">
            <h1>Add User</h1>
        </header>

        <form action="{{ route('admin.organizations.users.store', $organization) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control">
            </div>

            <button class="btn btn-primary" type="submit">Add User</button>
        </form>

    </div>
@stop
