@extends('layouts.front')

@section('content')
    <p>We are excited your {{ str_plural('student', $order->num_tickets) }} is planning to join us for PCC Students SMMR CMP 2016! We are looking forward to all that Jesus is going to do in students' lives during our time together in Daytona Beach!</p>

    <p>A confirmation email has been sent to <strong>{{ $order->user->person->email }}</strong>.</p>

    <p>If you have any questions, please feel free to check out the FAQ on the SMMR CMP website or contact our team at students@passioncitychurch.com or 678.366.9192.</p>
@stop
