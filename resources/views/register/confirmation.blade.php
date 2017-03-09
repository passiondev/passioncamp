@extends('layouts.pccstudents')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <p class="lead">We are excited your {{ str_plural('student', $order->num_tickets) }} is planning to join us for PCC Students SMMR CMP 2017! We are looking forward to all that Jesus is going to do in students' lives during our time together in Daytona Beach!</p>

                <p>You will be receiving an email from DOCUSIGN that must be filled out and returned within two weeks of registering your student. We will be in touch with more details soon.</p>

                <p>A confirmation email has been sent to <strong>{{ $order->user->person->email }}</strong>.</p>

                <p>If you have any questions, please feel free to check out the FAQ on the SMMR CMP website or contact our team at <a href="mailto:students@passioncitychurch.com">students@passioncitychurch.com</a> or 678.366.9192.</p>

                @include('order.receipt')
            </div>
        </div>
    </div>
@stop
