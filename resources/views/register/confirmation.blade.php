@extends('layouts.pccstudents')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <p class="lead">{!! $occurrence->confirmation !!}</p>

                <p>A confirmation email has been sent to <strong>{{ $order->user->person->email }}</strong>.</p>

                @if ($order->balance > 0)
                <p>
                    <a href="{{ route('magic.login') }}">Sign in to your account</a> to view your registration and pay your remaining balance.</a>
                </p>
                @endif

                <p>If you have any questions, please contact our team at <a href="mailto:students@passioncitychurch.com">students@passioncitychurch.com</a>.</p>

                @include('order.receipt')
            </div>
        </div>
    </div>
@stop
