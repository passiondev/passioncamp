@extends('layouts.pccstudents')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <p class="lead">We are so excited your student is planning to join us for Passion Camp! We are looking forward to all that Jesus is going to do in the lives of our students during our time together!</p>

                <p>A confirmation email has been sent to <strong>{{ $order->user->person->email }}</strong>.</p>

                @if ($order->balance > 0)
                <p>
                    <a href="{{ route('magic.login') }}">Sign in to your account</a> to view your registration and pay your remaining balance.</a>
                </p>
                @endif

                <div class="card mb-4" style="background-color: #EFF8FF; border: 0px; font-size: 0.875rem;">
                    <div class="p-4">
                        <h5>
                            Help Make Passion Camp Possible!
                        </h5>
                        <p>
                            We want as many students to experience Jesus at Passion Camp as possible this year! Would you consider partnering with us to help make Passion Camp a possibility for students who need financial assistance? We never want finances to keep a student from being able to join us. We are stunned every year by the generosity of our House!
                        </p>
                        <p style="font-size: 1rem; text-decoration:underline" class="mb-0"><a href="https://forms.ministryforms.net/viewForm.aspx?formId=59aa564b-7b60-4746-ae4b-8220307a9aa6" target="_blank">Partner with us and make a donation</a></p>
                    </div>
               </div>

                <p>If you have any questions, please contact our team at <a href="mailto:students@passioncitychurch.com">students@passioncitychurch.com</a>.</p>

                @include('order.receipt')
            </div>
        </div>
    </div>
@stop
