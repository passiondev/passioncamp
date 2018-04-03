@extends('layouts.bootstrap4')

@section('content')
<div class="container">
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header">Create Your Account</div>
                <div class="card-block">
                    <form action="{{ url("/register/{$user->id}/{$user->hash}") }}" method="POST">
                        {{ csrf_field() }}

                        <div class="form-group row {{ $errors->has('email') ? 'has-danger' : '' }}">
                            <label class="col-md-4 col-form-label text-md-right">Email Address</label>

                            <div class="col-md-6">
                                <p class="form-control-static">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('password') ? 'has-danger' : '' }}">
                            <label class="col-md-4 col-form-label text-md-right">Password</label>

                            <div class="col-md-6">
                                <input name="password" type="password" class="form-control">

                                @if ($errors->has('password'))
                                    <p class="form-control-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('password_confirmation') ? 'has-danger' : '' }}">
                            <label class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                            <div class="col-md-6">
                                <input name="password_confirmation" type="password" class="form-control">

                                @if ($errors->has('password_confirmation'))
                                    <p class="form-control-feedback">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
