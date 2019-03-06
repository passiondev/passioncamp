@extends('layouts.bootstrap4')

@section('content')
<div class="container">
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            @if (session('magic-link-sent'))
                <div class="alert alert-success">
                    <h4 class="alert-heading">Great!</h4>
                    <p>We sent an email to <strong>{{ session('magic-link-sent') }}</strong>. If this email has an account with Passion Students, you'll find a magic link that will sign you into your account.</p>
                    <p>The link will expire in 24 hours, so be sure to use it soon.</p>
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sign In</h3>
                    <h6 class="card-title">Enter your email address below and we'll send you a magic link to quickly sign in.</h6>
                </div>
                <div class="card-block">

                    <form method="POST" action="{{ url('/login') }}" >
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

                        <div class="form-group row">
                            <div class="col-lg-6 offset-lg-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : ''}}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 offset-lg-4">
                                <button type="submit" class="btn btn-primary">
                                    Send magic link
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
