@extends('layouts.pccstudents')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <p class="lead">We are excited your {{ str_plural('student', $order->num_tickets) }} {{ $order->num_tickets == 1 ? 'is' : 'are' }} planning to join PCC Students at Passion Camp 2018! We are looking forward to all that Jesus is going to do in students' lives during our time together in Daytona Beach!</p>

                <p>A confirmation email has been sent to <strong>{{ $order->user->person->email }}</strong>.</p>

                <p>If you have any questions, please contact our team at <a href="mailto:students@passioncitychurch.com">students@passioncitychurch.com</a>.</p>

                @include('order.receipt')
            </div>
        </div>
    </div>
@stop
