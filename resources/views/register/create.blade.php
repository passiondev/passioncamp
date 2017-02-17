@extends('layouts.front')

@section('head')
    <script>
        {{-- window.tickets_data = {!! json_encode(array_values(old('tickets') ?: [])) !!}; --}}
        window.ticket_price = {{ $ticket_price }};
        window.vuex = {!!json_encode([
            'num_tickets' => old('num_tickets', 1),
            'ticketData' => old('tickets', []),
            'fund_amount' => null,
            'fund_amount_other' => null,
        ]) !!}
    </script>
@endsection
@section('content')
    <register-form inline-template>
        <form method="POST" action="{{ route('register.store') }}" novalidate v-on:submit.prevent="submitHandler">
            {{ csrf_field() }}
            @include('errors.validation')

            <div class="row">
                <section class="col-md-6">
                    <header>
                        <h4>Parent Information</h4>
                    </header>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="contact__first_name" class="control-label">First Name</label>
                            <div class="form-controls">
                                <input type="text" name="contact[first_name]" id="contact__first_name" class="form-control" value="{{ old('contact.first_name') }}">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="contact__last_name" class="control-label">Last Name</label>
                            <div class="form-controls">
                                <input type="text" name="contact[last_name]" id="contact__last_name" class="form-control" value="{{ old('contact.last_name') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-7">
                            <label for="contact__email" class="control-label">Email Address</label>
                            <div class="form-controls">
                                <input type="text" name="contact[email]" id="contact__email" class="form-control" value="{{ old('contact.email') }}">
                            </div>
                            <p class="help-block" style="font-size:.85em;margin-bottom: 0;">Please provide a parent's email address to ensure your registration confirmation is received</p>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="contact__phone" class="control-label">Phone Number</label>
                            <div class="form-controls">
                                <input type="text" name="contact[phone]" id="contact__phone" class="form-control" value="{{ old('contact.phone') }}">
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="row" style="margin-bottom:20px">
                <div class="form-group col-md-4">
                    <label for="num_tickets" class="control-label">Number of Students</label>
                    <input type="number" name="num_tickets" id="num_tickets" class="form-control input-lg" v-model="num_tickets" min="1" max="200">
                </div>
            </div>

            <section class="row tickets">
                <ticket v-for="ticket in tickets" :ticket="ticket"></ticket>
            </section>

            <section>
                <header>
                    <h4>Help Make Camp Possible!</h4>
                    <p>Taking hundreds of students to camp is not a cheap endeavor, but we strongly believe it is going to be a life-changing week for our students and our church. We have aimed to keep the cost as low as possible so more students can afford to go, but there is still  a sizeable gap between what students are paying and our costs as a church. If you are able or if you know of anyone who wants to invest in this week for our students, you can add a gift to your payment, and we would be massively grateful!</p>

                    <p>Any and all gifts will be a huge help, but here are a few specific things you can give towards:</p>
                    <ul>
                        <li>A small group leader's spot (We are taking 100-120 leaders!)</li>
                        <li>The charter buses (Each bus costs $5,000 and we are taking 12-14 buses!)</li>
                        <li>To help a student who can’t afford camp (We don’t want anyone to miss out!)</li>
                    </ul>
                </header>
                <div class="form-group">
                    <label class="control-label">Donation Amount</label>
                    <div class="radio">
                        <label>
                            <input type="radio" name="fund_amount" value="10" v-model.number="fund_amount">
                            $10
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="fund_amount" value="25" v-model.number="fund_amount">
                            $25
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="fund_amount" value="50" v-model.number="fund_amount">
                            $50
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="fund_amount" value="other" v-model="fund_amount">
                            Other
                        </label>
                        - $<input type="number" name="fund_amount_other" min="0" max="99999" class="form-control" style="width: 6em; text-align: right; display: inline-block" v-model.number="fund_amount_other">
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="fund_amount" value="0" v-model.number="fund_amount">
                            No thanks
                        </label>
                    </div>
                </div>
            </section>

            <div class="row">
                <section class="col-md-4">
                    <h4>Billing Address</h4>
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
                </section>
            </div>
            <div class="row">
                <section class="col-md-6">
                    <header style="margin-bottom: 15px">
                        <h4>Payment Information</h4>
                        <h4>Amount Due: @{{ full_amount | currency }}</h4>
                    </header>
                    <div class="payment_method payment_method--credit">
                        <p class="payment-errors text-danger"></p>
                        <div class="form-group">
                            <label for="cc_number" class="control-label">Card Number</label>
                            <input type="text" id="cc_number" class="form-control js-form-input-card-number" data-stripe="number" required style="width:auto;max-width:100%" size="40">
                        </div>

                        <div class="form-group-inline">
                            <div class="form-group">
                                <label for="cc_exp_month" class="control-label">Expiration</label>
                                <input type="text" id="cc_expiry" class="form-control js-form-input-card-expiry" placeholder="mm / yy" data-stripe="exp" required>
                            </div>
                            <div class="form-group">
                                <label for="cc_cvc" class="control-label">CVC</label>
                                <input id="cc_cvc" type="text" size="8" data-stripe="cvc" class="form-control js-form-input-card-cvc">
                            </div>
                        </div>
                    </div>
                </section>
            </div>


            <section class="form-actions">
                <p><i>Upon clicking submit, your credit card will be charged @{{ full_amount | currency }} for your Passion Camp registration.</i></p>
                <button class="btn btn-primary btn-lg">Submit Registration</button>
            </section>
        </form>
    </register-form>
@endsection

@section('foot')
    <script src="https://js.stripe.com/v2/"></script>
    <script>
        Stripe.setPublishableKey('{{ config('services.stripe.key') }}');
    </script>
@endsection