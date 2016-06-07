@extends('layouts.semantic')

@section('content')
    <div class="container">
        <div class="ui centered grid">
            <div class="column" style="max-width:450px">
                <form class="ui large form" role="form" method="POST" action="{{ url('/login') }}">
                    {!! csrf_field() !!}

                    <div class="field {{ $errors->has('email') ? 'has-error' : '' }}">
                        <label>Email Address</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="email" value="{{ old('email') }}">

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="field {{ $errors->has('password') ? ' has-error' : '' }}">
                        <label class="">Password</label>

                        <div class="col-md-6">
                            <input type="password" class="form-control" name="password">

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="field">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember"> Remember Me
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="ui button primary">
                        Login
                    </button>

                    <small style="margin-left:1rem"><a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a></small>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
