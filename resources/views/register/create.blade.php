@extends('layouts.pccstudents', ['occurrence' => $occurrence])

@section('content')
<div class="container">
    <register-form
        action="{{ route('register.store') }}"
        stripe-elements="card-element"
        :can-pay-deposit="@json($can_pay_deposit)"
        :prop-ticket-price="@json($ticketPrice)"
        :grades="@json(config('occurrences.pcc.grades'))"
    ></register-form>
</div>
@endsection

@section('foot')
    <script>
        const stripe = Stripe('{{ config('settings.stripe.pcc.key') }}');
    </script>
@endsection
