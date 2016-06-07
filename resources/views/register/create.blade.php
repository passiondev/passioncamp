@extends('layouts.front')

@section('head')
    <script>
        window.tickets_data = {!! json_encode(array_values(old('tickets') ?: [])) !!};
        window.ticket_price = {{ $ticket_price }};
    </script>
@endsection
@section('content')
    <register-form inline-template>
        {{ Form::open(['route' => 'register.store', 'novalidate', 'v-on:submit.prevent' => 'submitHandler']) }}
            @include('errors.validation')

            <div class="row">
                <section class="col-md-6">
                    <header>
                        <h4>Parent Information</h4>
                    </header>
                    <div class="row">
                        <div class="form-group col-md-6">
                            {{ Form::label('contact[first_name]', 'First Name', ['class' => 'control-label']) }}
                            <div class="form-controls">
                                {{ Form::text('contact[first_name]', null, ['id' => 'contact__first_name', 'class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            {{ Form::label('contact[last_name]', 'Last Name', ['class' => 'control-label']) }}
                            <div class="form-controls">
                                {{ Form::text('contact[last_name]', null, ['id' => 'contact__last_name', 'class' => 'form-control']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-7">
                            {{ Form::label('contact[email]', 'Email Address', ['class' => 'control-label']) }}
                            <div class="form-controls">
                                {{ Form::email('contact[email]', null, ['id' => 'contact__email', 'class' => 'form-control']) }}
                            </div>
                            <p class="help-block" style="font-size:.85em;margin-bottom: 0;">Please provide a parent's email address to ensure your registration confirmation is received</p>
                        </div>
                        <div class="form-group col-md-5">
                            {{ Form::label('contact[phone]', 'Phone Number', ['class' => 'control-label']) }}
                            <div class="form-controls">
                                {{ Form::text('contact[phone]', null, ['id' => 'contact__phone', 'class' => 'form-control']) }}
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="row" style="margin-bottom:20px">
                <div class="form-group col-md-4">
                    {{ Form::label('num_tickets', 'Number of Students', ['class' => 'control-label']) }}
                    {{ Form::number('num_tickets', 1, ['id' => 'num_tickets', 'class' => 'form-control input-lg', 'v-model' => 'num_tickets', 'number', 'min' => 1, 'max' => 200]) }}
                </div>
            </div>

            <section class="row tickets" v-for="row in ticket_rows">
                <div v-for="ticket in row" class="col-md-5 ticket" v-bind:class="{'col-md-offset-1': (ticket.number % 2 == 0)}">
                    <h4>Student #@{{ ticket.number }}</h4>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label class="control-label" for="tickets_@{{ ticket.number }}__first_name">First Name</label>
                            <input class="form-control" type="text" id="tickets_@{{ ticket.number }}__first_name" name="tickets[@{{ ticket.number }}][first_name]" v-model="ticket.first_name">
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="control-label" for="tickets_@{{ ticket.number }}__last_name">Last Name</label>
                            <input class="form-control" type="text" id="tickets_@{{ ticket.number }}__last_name" name="tickets[@{{ ticket.number }}][last_name]" v-model="ticket.last_name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-7">
                            <label class="control-label" for="tickets_@{{ ticket.number }}__email">Email Address</label>
                            <input class="form-control" type="email" id="tickets_@{{ ticket.number }}__email" name="tickets[@{{ ticket.number }}][email]" v-model="ticket.email">
                        </div>
                        <div class="form-group col-sm-5">
                            <label class="control-label" for="tickets_@{{ ticket.number }}__phone">Phone Number</label>
                            <input class="form-control" type="text" id="tickets_@{{ ticket.number }}__phone" name="tickets[@{{ ticket.number }}][phone]" v-model="ticket.phone">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label class="control-label" for="tickets_@{{ ticket.number }}__birthdate">Birthdate</label>
                            <input class="form-control js-form-input-date" placeholder="mm/dd/yyyy" type="text" id="tickets_@{{ ticket.number }}__birthdate" name="tickets[@{{ ticket.number }}][birthdate]" v-model="ticket.birthdate">
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="control-label" for="tickets_@{{ ticket.number }}__gender">Gender</label>
                            <div class="form-controls form-controls--radio">
                                <label class="radio-inline">
                                    <input type="radio" value="M" name="tickets[@{{ ticket.number }}][gender]" v-model="ticket.gender" id="tickets_@{{ ticket.number }}__gender"> Male
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" value="F" name="tickets[@{{ ticket.number }}][gender]" v-model="ticket.gender" id="tickets_@{{ ticket.number }}__gender"> Female
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="control-label" for="tickets_@{{ ticket.number }}__shirtsize">Shirt Size</label>
                            <select class="form-control" id="tickets_@{{ ticket.number }}__shirtsize" name="tickets[@{{ ticket.number }}][shirtsize]" v-model="ticket.shirtsize"><option value="0"></option><option value="1">XS</option><option value="2">S</option><option value="3">M</option><option value="4">L</option><option value="5">XL</option></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label class="control-label" for="tickets_@{{ ticket.number }}__grade">Grade</label>
                            <select class="form-control" id="tickets_@{{ ticket.number }}__grade" name="tickets[@{{ ticket.number }}][grade]" v-model="ticket.grade"><option value="0"></option><option value="6">6th</option><option value="7">7th</option><option value="8">8th</option><option value="9">9th</option><option value="10">10th</option><option value="11">11th</option><option value="12">12th</option></select>
                            <p class="help-block text-muted" style="font-size:.85em;margin-bottom: 0;">Grade completed as of Spring 2016</p>
                        </div>
                        <div class="form-group col-sm-8">
                            <label class="control-label" for="tickets_@{{ ticket.number }}__school">School</label>
                            <input class="form-control" type="text" id="tickets_@{{ ticket.number }}__school" name="tickets[@{{ ticket.number }}][school]" v-model="ticket.school">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="tickets_@{{ ticket.number }}__allergies">Allergies</label>
                        <textarea class="form-control" id="tickets_@{{ ticket.number }}__allergies" name="tickets[@{{ ticket.number }}][allergies]" v-model="ticket.allergies" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                    <label class="control-label" for="tickets_@{{ ticket.number }}__roommate_requested">Roommate Requested</label>
                    <input class="form-control" type="text" name="tickets[@{{ ticket.number }}][roommate_requested]" v-model="ticket.roommate_requested" id="tickets_@{{ ticket.number }}__roommate_requested">
                    </div>
                </div>
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
                            {!! Form::radio('fund_amount', 10, null, ['v-model'=>'fund_amount', 'number']) !!}
                            $10
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            {!! Form::radio('fund_amount', 25, null, ['v-model'=>'fund_amount', 'number']) !!}
                            $25
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            {!! Form::radio('fund_amount', 50, null, ['v-model'=>'fund_amount', 'number']) !!}
                            $50
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            {!! Form::radio('fund_amount', 'other', null, ['v-model'=>'fund_amount']) !!}
                            Other
                        </label>
                        - ${!! Form::number('fund_amount_other', null, ['class' => 'form-control', 'v-model' => 'fund_amount_other', 'number', 'min' => 0, 'max' => 99999, 'style' => 'width: 6em; text-align: right; display: inline-block']) !!}.00
                    </div>
                    <div class="radio">
                        <label>
                            {!! Form::radio('fund_amount', 0, null, ['v-model'=>'fund_amount', 'number']) !!}
                            No thanks
                        </label>
                    </div>
                </div>
            </section>

            <div class="row">
                <section class="col-md-4">
                    <h4>Billing Address</h4>
                    <div class="form-group">
                        {!! Form::label('billing__street', 'Street', ['class'=>'control-label']) !!}
                        {!! Form::text('billing[street]', null, ['class'=>'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('billing__city', 'City', ['class'=>'control-label']) !!}
                        {!! Form::text('billing[city]', null, ['class'=>'form-control']) !!}
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6">
                            {!! Form::label('billing__state', 'State', ['class'=>'control-label']) !!}
                            {!! Form::text('billing[state]', null, ['class'=>'form-control']) !!}
                        </div>
                        <div class="form-group col-sm-6">
                            {!! Form::label('billing__zip', 'Zip Code', ['class'=>'control-label']) !!}
                            {!! Form::text('billing[zip]', null, ['class'=>'form-control']) !!}
                        </div>
                    </div>
                </section>
            </div>
            <div class="row">
                <section class="col-md-6">
                    <header style="margin-bottom: 15px">
                        <h4>Payment Information</h4>
                    </header>
                    <div class="form-group">
                        <div class="radio">
                            <label>
                                {{ Form::radio('payment_amount_type', 'full', true, ['v-model' => 'payment_amount_type']) }} Full Amount @{{ full_amount | currency }}
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                {{ Form::radio('payment_amount_type', 'deposit', null, ['v-model' => 'payment_amount_type']) }} Deposit @{{ deposit_amount | currency }}
                            </label>
                        </div>
                        <p>Summer Camp Registration must be paid by June 13th to ensure that your student keeps their spot. Deposits are non-refundable.</p>
                    </div>
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
                <button class="btn btn-primary btn-lg">Submit Registration</button>
            </section>
        {{ Form::close() }}
    </register-form>
@endsection

@section('foot')
    <script src="https://js.stripe.com/v2/"></script>
    <script>
        Stripe.setPublishableKey('{{ config('services.stripe.key') }}');
    </script>
@endsection