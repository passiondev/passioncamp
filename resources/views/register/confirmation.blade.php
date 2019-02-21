@extends('layouts.pccstudents')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <p class="lead">{!! $occurrence->confirmation !!}</p>

                <p>A confirmation email has been sent to <strong>{{ $order->user->person->email }}</strong>.</p>

                <p>If you have any questions, please contact our team at <a href="mailto:students@passioncitychurch.com">students@passioncitychurch.com</a>.</p>

                @include('order.receipt')
            </div>
        </div>
    </div>
@stop
