@extends('layouts.bootstrap4')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header">Reset Password</div>
                <div class="card-block">
                    <form method="POST" action="{{ url('/password/reset') }}">
                        {!! csrf_field() !!}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-lg-4 col-form-label text-lg-right">E-Mail Address</label>

                            <div class="col-lg-6">
                                <input type="email" class="form-control" name="email" value="{{ $email or old('email') }}">

                                @if ($errors->has('email'))
                                    <p class="form-control-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-lg-4 col-form-label text-lg-right">Password</label>

                            <div class="col-lg-6">
                                <input type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <p class="form-control-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="col-lg-4 col-form-label text-lg-right">Confirm Password</label>
                            <div class="col-lg-6">
                                <input type="password" class="form-control" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <p class="form-control-feedback">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-6 offset-lg-4">
                                <button type="submit" class="btn btn-primary">
                                    Reset Password
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
