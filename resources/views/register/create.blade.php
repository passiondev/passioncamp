@extends('layouts.pccstudents')

@section('head')
    <script>
        window.ticket_price = {{ $ticket_price }};
        window.vuex = {!! json_encode([
            'num_tickets' => old('num_tickets', 1),
            'ticketData' => old('tickets', []),
            'fund_amount' => old('fund_amount'),
            'fund_amount_other' => old('fund_amount_other'),
            'payment_type' => old('payment_type', 'full')
        ]) !!}
    </script>
@endsection
@section('content')
<div class="container">
    <register-form inline-template>
        <form class="register-form" method="POST" action="{{ route('register.store') }}" novalidate v-on:submit.prevent="submitHandler">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-7">

                    @include('errors.validation')

                    <section>
                        <div class="card card--registration">
                            <header class="card-header">
                                <h2>Parent Information</h2>
                            </header>
                            <div class="card-block">
                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <label for="contact__first_name">First Name</label>
                                        <div class="form-controls">
                                            <input type="text" name="contact[first_name]" id="contact__first_name" class="form-control" value="{{ old('contact.first_name') }}">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="contact__last_name">Last Name</label>
                                        <div class="form-controls">
                                            <input type="text" name="contact[last_name]" id="contact__last_name" class="form-control" value="{{ old('contact.last_name') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <label for="contact__email">Email Address</label>
                                        <div class="form-controls">
                                            <input type="text" name="contact[email]" id="contact__email" class="form-control" value="{{ old('contact.email') }}">
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="contact__phone">Phone Number</label>
                                        <div class="form-controls">
                                            <input type="text" name="contact[phone]" id="contact__phone" class="form-control" value="{{ old('contact.phone') }}">
                                        </div>
                                    </div>
                                </div>
                                <p class="form-text text-muted" style="margin-top:-.25rem; font-size:80%; font-style: italic;">Please provide a parent's email address to ensure your registration confirmation is received.</p>
                            </div>
                        </div>
                    </section>

                    <section>
                        <div class="card card--registration">
                            <header class="card-header">
                                <h2>Student Infromation</h2>
                            </header>
                            <div class="card-block">
                                <div class="row">
                                    <div class="form-group col-8 col-lg-6">
                                        <label for="num_tickets">Number of Students</label>
                                        <input type="number" name="num_tickets" id="num_tickets" class="form-control form-control-lg" v-model="num_tickets" min="1" max="200">
                                    </div>
                                    <div class="form-group col">
                                        <label>Total</label>
                                        <p class="form-control-static" style="font-size:1.5rem">@{{ ticket_total | currency }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tickets">
                            <ticket v-for="(ticket, index) in tickets" :index="index" :ticket="ticket"></ticket>
                        </div>
                    </section>

                    <section>
                    <div class="card card--registration">
                        <div class="card-block">
                            <h4>Help Make Camp Possible!</h4>
                            <p>Taking hundreds of students to camp is not a cheap endeavor, but we strongly believe it is going to be a life-changing week for our students and our church. We have aimed to keep the cost as low as possible so more students can afford to go, but there is still  a sizeable gap between what students are paying and our costs as a church. If you are able or if you know of anyone who wants to invest in this week for our students, you can add a gift to your payment, and we would be massively grateful!</p>

                            <p>Any and all gifts will be a huge help, but here are a few specific things you can give towards:</p>
                            <ul>
                                <li>A small group leader's spot (We are taking 100-120 leaders!)</li>
                                <li>The charter buses (Each bus costs $5,000 and we are taking 12-14 buses!)</li>
                                <li>To help a student who can’t afford camp (We don’t want anyone to miss out!)</li>
                            </ul>
                            <fieldset>
                                <legend>Donation Amount</legend>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="fund_amount" value="10" v-model.number="fund_amount">
                                        $10
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="fund_amount" value="25" v-model.number="fund_amount">
                                        $25
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="fund_amount" value="50" v-model.number="fund_amount">
                                        $50
                                    </label>
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="fund_amount" value="other" onchange="document.querySelector('#fund_amount_other').focus()" v-model="fund_amount">
                                        Other
                                    </label>
                                    - $<input type="number" name="fund_amount_other" id="fund_amount_other" min="0" max="99999" class="form-control form-control-sm nospin" style="width: 6em; text-align: right; display: inline-block" v-model.number="fund_amount_other">
                                </div>
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input type="radio" class="form-check-input" name="fund_amount" value="0" v-model.number="fund_amount">
                                        No thanks
                                    </label>
                                </div>
                            </fie>
                        </div>
                    </section>

                    <section>
                        <div class="card card--registration">
                            <header class="card-header">
                                <h2>Payment Information</h2>
                            </header>
                            <div class="card-block">
                                <h4>Billing Address</h4>
                                <div class="row">
                                    <div class="col-lg-10 col-xl-8">
                                        <div class="form-group">
                                            <label for="billing__street">Street</label>
                                            <input type="text" name="billing[street]" id="billing__street" class="form-control" value="{{ old('billing.street') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="billing__city">City</label>
                                            <input type="text" name="billing[city]" id="billing__city" class="form-control" value="{{ old('billing.city') }}">
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-sm-6">
                                                <label for="billing__state">State</label>
                                                <input type="text" name="billing[state]" id="billing__state" class="form-control" value="{{ old('billing.state') }}">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label for="billing__zip">Zip Code</label>
                                                <input type="text" name="billing[zip]" id="billing__zip" class="form-control" value="{{ old('billing.zip') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-block mb-3 hidden-lg-up">
                                <div class="card">
                                    <header class="card-header">
                                        <h3 class="mt-1">Order Summary</h3>
                                    </header>
                                    <div class="card-block">
                                        <ul class="order-summary list-unstyled mb-0">
                                            <li class="row order-summary__item py-1">
                                                <div class="col">
                                                    <p class="mb-0">SMMR CMP Ticket x@{{ num_tickets }}</p>
                                                    <span class="text-muted">@{{ ticket_price | currency }}</span>
                                                </div>
                                                <div class="col text-right">
                                                    <strong>@{{ ticket_total | currency }}</strong>
                                                </div>
                                            </li>
                                            <li class="row order-summary__item" v-show="donation_total > 0">
                                                <div class="col">
                                                    <p class="mb-0">Donation</p>
                                                </div>
                                                <div class="col text-right">
                                                    <strong>@{{ donation_total | currency }}</strong>
                                                </div>
                                            </li>
                                            <li class="row order-summary__item order-summary__total">
                                                <div class="col">
                                                    <p class="mb-0"><strong>Total</strong></p>
                                                </div>
                                                <div class="col text-right">
                                                    <strong>@{{ full_amount | currency }}</strong>
                                                </div>
                                            </li>
                                            <li class="row order-summary__item">
                                                <div class="col">
                                                    Deposit Amount
                                                </div>
                                                <div class="col text-right">
                                                    @{{ deposit_amount | currency }}
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="card-block">
                                <h4>Payment Options</h4>
                                <div class="form-check-pill">
                                    <input id="payment_type--full" type="radio" name="payment_type" value="full" v-model="payment_type">
                                    <label for="payment_type--full">
                                        @icon('checkmark') Pay Full Amount
                                    </label>
                                </div>
                                <div class="form-check-pill">
                                    <input id="payment_type--deposit" type="radio" name="payment_type" value="deposit" v-model="payment_type">
                                    <label for="payment_type--deposit">
                                        @icon('checkmark') Pay Deposit
                                    </label>
                                </div>
                                <p class="form-text"><em>**Deposits are non-refundable.</em></p>
                            </div>

                            <div class="card-block">
                                <h4>Credit Card</h4>
                                <div class="row">
                                    <div class="col-lg-10 col-xl-8">
                                        <div class="form-group">
                                            <label for="cc_number">Card Number</label>
                                            <input type="text" id="cc_number" class="form-control js-form-input-card-number" required v-model="Payment.card_number">
                                        </div>
                                        <div class="row">
                                            <div class="form-group col">
                                                <label for="cc_exp_month">Expiration</label>
                                                <input type="text" id="cc_expiry" class="form-control js-form-input-card-expiry" placeholder="mm / yy" required v-model="Payment.card_exp">
                                            </div>
                                            <div class="form-group col">
                                                <label for="cc_cvc">CVC</label>
                                                <input id="cc_cvc" type="text" size="8" class="form-control js-form-input-card-cvc" v-model="Payment.card_cvc">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>


                    <section>
                        <p class="lead">Full payment is due by May 1st. <strong>Full Summer Camp registration is non-refundable after this date.</strong></p>

                        <p><i>Upon clicking submit, your credit card will be charged <strong>@{{ payment_amount | currency }}</strong> for your Passion Camp registration.</i></p>

                        <div class="alert alert-danger" v-if="Payment.errors.length">
                            <ul class="mb-0">
                                <li v-for="error in Payment.errors">
                                    @{{ error }}
                                </li>
                            </ul>
                        </div>

                        <button :disabled="Payment.occupied" class="btn btn-primary btn-lg">Submit Registration</button>
                    </section>

                </div>
                <div class="col-lg-5 hidden-md-down">
                    <div class="sticky-top" style="padding-top:2rem;margin-top:-2rem">
                        <div class="card">
                            <header class="card-header">
                                <h3 class="mt-1">Order Summary</h3>
                            </header>
                            <div class="card-block">
                                <ul class="order-summary list-unstyled mb-0">
                                    <li class="row order-summary__item py-1">
                                        <div class="col">
                                            <p class="mb-0">SMMR CMP Ticket x@{{ num_tickets }}</p>
                                            <span class="text-muted">@{{ ticket_price | currency }}</span>
                                        </div>
                                        <div class="col text-right">
                                            <strong>@{{ ticket_total | currency }}</strong>
                                        </div>
                                    </li>
                                    <li class="row order-summary__item" v-show="donation_total > 0">
                                        <div class="col">
                                            <p class="mb-0">Donation</p>
                                        </div>
                                        <div class="col text-right">
                                            <strong>@{{ donation_total | currency }}</strong>
                                        </div>
                                    </li>
                                    <li class="row order-summary__item order-summary__total">
                                        <div class="col">
                                            <p class="mb-0"><strong>Total</strong></p>
                                        </div>
                                        <div class="col text-right">
                                            <strong>@{{ full_amount | currency }}</strong>
                                        </div>
                                    </li>
                                    <li class="row order-summary__item px-4">
                                        <div class="col">
                                            Deposit Amount
                                        </div>
                                        <div class="col text-right">
                                            @{{ deposit_amount | currency }}
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </register-form>
</div>
@endsection

@section('foot')
    <script src="https://js.stripe.com/v2/"></script>
    <script>
        Stripe.setPublishableKey('{{ config('services.stripe.key') }}');
    </script>
@endsection
