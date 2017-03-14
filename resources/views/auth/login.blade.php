@extends('layouts.bootstrap4')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header">Login</div>
                <div class="card-block">
                    <form method="POST" action="{{ url('/login') }}">
                        {!! csrf_field() !!}

                        <div class="form-group row {{ $errors->has('email') ? 'has-danger' : '' }}">
                            <label for="email" class="col-lg-4 col-form-label text-lg-right">Email Address</label>

                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="email" id="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <p class="form-control-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('password') ? ' has-danger' : '' }}">
                            <label for="password" class="col-lg-4 col-form-label text-lg-right">Password</label>

                            <div class="col-lg-6">
                                <input type="password" class="form-control" name="password" id="password">

                                @if ($errors->has('password'))
                                    <p class="form-control-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-6 offset-lg-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : ''}}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-8 offset-lg-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>

                                <a class="btn btn-link" href="{{ url('/password/reset') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
