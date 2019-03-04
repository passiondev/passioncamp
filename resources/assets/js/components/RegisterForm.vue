<template>
    <form ref="form" class="register-form" method="POST" novalidate v-on:submit.prevent="submitHandler">

        <div id="form-submission-alert" class="alert alert-danger" role="alert" v-if="errors.any()">
            There was an error with your submission. {{ errors.first('payment')}}
        </div>

        <div class="row">
            <div class="col-md-7">
                <section>
                    <div class="card card--registration">
                        <header class="card-header">
                            <h2>Parent Information</h2>
                        </header>
                        <div class="card-block">
                            <div class="row">
                                <div class="form-group col-lg-6" :class="{ 'has-danger' : errors.has('first_name') }">
                                    <label for="contact__first_name" class="form-control-label">First Name</label>
                                    <input type="text" v-model="form.first_name" id="contact__first_name" class="form-control">
                                    <div class="form-control-feedback" v-if="errors.has('first_name')">
                                        {{ errors.first('first_name') }}
                                    </div>
                                </div>
                                <div class="form-group col-lg-6" :class="{ 'has-danger' : errors.has('last_name') }">
                                    <label for="contact__last_name" class="form-control-label">Last Name</label>
                                    <input type="text" v-model="form.last_name" id="contact__last_name" class="form-control">
                                    <div class="form-control-feedback" v-if="errors.has('last_name')">
                                        {{ errors.first('last_name') }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6" :class="{ 'has-danger' : errors.has('email') }">
                                    <label for="contact__email" class="form-control-label">Email Address</label>
                                    <input type="email" v-model="form.email" id="contact__email" class="form-control">
                                    <div class="form-control-feedback" v-if="errors.has('email')">
                                        {{ errors.first('email') }}
                                    </div>
                                </div>
                                <div class="form-group col-lg-6" :class="{ 'has-danger' : errors.has('phone') }">
                                    <label for="contact__phone" class="form-control-label">Phone Number</label>
                                    <input type="tel" v-model="form.phone" id="contact__phone" class="form-control">
                                    <div class="form-control-feedback" v-if="errors.has('phone')">
                                        {{ errors.first('phone') }}
                                    </div>
                                </div>
                            </div>
                            <p class="form-text text-muted" style="margin-top:-.25rem; font-size:80%; font-style: italic;">Please provide a parent's email address to ensure your registration confirmation is received.</p>
                        </div>
                        <div class="card-block">
                            <h4>Address</h4>
                            <div class="row">
                                <div class="col-lg-10 col-xl-8">
                                    <div class="form-group" :class="{ 'has-danger' : errors.has('street') }">
                                        <label for="billing__street" class="form-control-label">Street</label>
                                        <input type="text" v-model="form.street" id="billing__street" class="form-control">
                                        <div class="form-control-feedback" v-if="errors.has('street')">
                                            {{ errors.first('street') }}
                                        </div>
                                    </div>
                                    <div class="form-group" :class="{ 'has-danger' : errors.has('city') }">
                                        <label for="billing__city" class="form-control-label">City</label>
                                        <input type="text" v-model="form.city" id="billing__city" class="form-control">
                                        <div class="form-control-feedback" v-if="errors.has('city')">
                                            {{ errors.first('city') }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6" :class="{ 'has-danger' : errors.has('state') }">
                                            <label for="billing__state" class="form-control-label">State</label>
                                            <input type="text" v-model="form.state" id="billing__state" class="form-control">
                                            <div class="form-control-feedback" v-if="errors.has('state')">
                                                {{ errors.first('state') }}
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6" :class="{ 'has-danger' : errors.has('zip') }">
                                            <label for="billing__zip" class="form-control-label">Zip Code</label>
                                            <input type="text" v-model="form.zip" id="billing__zip" class="form-control">
                                            <div class="form-control-feedback" v-if="errors.has('zip')">
                                                {{ errors.first('zip') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="card card--registration">
                        <header class="card-header">
                            <h2>Student Information</h2>
                        </header>
                        <div class="card-block">
                            <div class="row">
                                <div class="form-group col-8 col-lg-6" :class="{ 'has-danger' : errors.has('num_tickets') }">
                                    <label for="num_tickets" class="form-control-label">Number of Students</label>
                                    <input type="number" v-model="num_tickets" id="num_tickets" class="form-control form-control-lg" min="1" max="200">
                                    <div class="form-control-feedback" v-if="errors.has('num_tickets')">
                                        {{ errors.first('num_tickets') }}
                                    </div>
                                </div>
                                <div class="form-group col">
                                    <label>Total</label>
                                    <p class="form-control-static" style="font-size:1.5rem">{{ ticket_total | currency }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tickets">
                        <ticket
                            v-for="(ticket, index) in form.tickets"
                            :key="index"
                            :index="index"
                            :errors="errors | forTicket(index)"
                            :grades="grades"
                            :id="`ticket_${index}`"
                            @input="(ticket) => { form.tickets[index] = ticket }"
                        ></ticket>
                    </div>

                    <div class="card mt-4" style="background-color:#f1f5f8; border: 0; font-size: .875rem">
                        <div class="card-block">
                            <div class="form-group mb-0">
                                <label for="rep" class="form-control-label mb-1">Passion Camp Rep <span style="color:#b8c2cc">(optional)</span></label>
                                <input type="text" placeholder="Rep Name" class="form-control" v-model="form.rep">
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="card card--registration">
                        <div class="card-block">
                            <h4>Help Make Passion Camp Possible!</h4>
                            <p>We want as many students to experience Jesus at Passion Camp as possible this year! Would you consider partnering with us to help make Passion Camp a possibility for students who need financial assistance? We never want finances to keep a student from being able to join us. We are stunned every year by the generosity of our House!</p>
                            <p>Any and all gifts will be a huge help, but here are a few specific things you can give towards:</p>
                            <ul>
                                <li>A Family Group Leader's spot (We will have 40-50 leaders with us.)</li>
                                <li>Ground Transportation (Each van costs $420 and we will have 10-12 vans!)</li>
                                <li>To help a student who can’t afford Winter WKND (We don’t want anyone to miss out!)</li>
                            </ul>

                            <a href="https://www.kindridgiving.com/f/f2?formid=59aa564b-7b60-4746-ae4b-8220307a9aa6" target="_blank" style="font-weight: 700; font-size: 1.125rem; text-decoration: underline;">Partner with us and make a donation</a>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="card card--registration">
                        <header class="card-header">
                            <h2>Payment Information</h2>
                        </header>

                        <div class="card-block mb-3 hidden-lg-up">
                            <div class="card">
                                <header class="card-header">
                                    <h3 class="mt-1">Order Summary</h3>
                                </header>
                                <div class="card-block">
                                    <ul class="order-summary list-unstyled mb-0">
                                        <li class="row order-summary__item py-1">
                                            <div class="col">
                                                <p class="mb-0">Passion Camp Ticket x{{ form.num_tickets }}</p>
                                                <span class="text-muted">{{ ticket_price | currency }}</span>
                                            </div>
                                            <div class="col text-right">
                                                <strong>{{ ticket_total | currency }}</strong>
                                            </div>
                                        </li>
                                        <li class="row order-summary__item" v-show="donation_total > 0">
                                            <div class="col">
                                                <p class="mb-0">Donation</p>
                                            </div>
                                            <div class="col text-right">
                                                <strong>{{ donation_total | currency }}</strong>
                                            </div>
                                        </li>
                                        <li style="border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; padding: 1rem 0">
                                            <div class="flex form-inline">
                                                <input type="text" v-model="form.code" id="" class="form-control" placeholder="Discount Code" style="flex: 1">
                                                <button class="btn btn-secondary ml-3" @click.prevent="applyDiscountCode">Apply</button>
                                            </div>
                                        </li>
                                        <li class="row order-summary__item order-summary__total">
                                            <div class="col">
                                                <p class="mb-0"><strong>Total</strong></p>
                                            </div>
                                            <div class="col text-right">
                                                <strong>{{ full_amount | currency }}</strong>
                                            </div>
                                        </li>
                                        <li class="row order-summary__item" v-if="canPayDeposit">
                                            <div class="col">
                                                Deposit Amount
                                            </div>
                                            <div class="col text-right">
                                                {{ deposit_amount | currency }}
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="card-block form-group" v-if="canPayDeposit" :class="{ 'has-danger' : errors.has('payment_type') }">
                            <h4>Payment Options</h4>
                            <div class="form-check-pill">
                                <input id="payment_type--deposit" type="radio" v-model="form.payment_type" value="deposit">
                                <label for="payment_type--deposit" dusk="pay-deposit">
                                    <svg class="icon" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" fill-rule="evenodd"/>
                                    </svg>
                                    Pay Deposit
                                </label>
                            </div>
                            <div class="form-check-pill">
                                <input id="payment_type--full" type="radio" v-model="form.payment_type" value="full">
                                <label for="payment_type--full">
                                    <svg class="icon" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" fill-rule="evenodd"/>
                                    </svg>
                                    Pay Full Amount
                                </label>
                            </div>
                            <div class="form-control-feedback" v-if="errors.has('payment_type')">
                                {{ errors.first('payment_type') }}
                            </div>
                            <p class="form-text"><em>**Deposits are non-refundable.</em></p>
                        </div>
                        <template v-else>
                            <input type="hidden" v-model="form.payment_type" value="full">
                        </template>

                        <div class="card-block">
                            <h4>Credit or debit card</h4>
                            <div class="row">
                                <div class="col-lg-10 col-xl-8">
                                    <div id="card-element" class="form-control"></div>
                                </div>
                            </div>
                            <div class="text-danger mt-2" v-if="Payment.errors.length">
                                <ul class="list-unstyled mb-0">
                                    <li v-for="error in Payment.errors">
                                        {{ error }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <p class="lead">Full payment is due by May 5th. <strong>Full Passion Camp registration is non-refundable after this date.</strong></p>

                    <p><i>Upon clicking submit, your credit card will be charged <strong>{{ payment_amount | currency }}</strong> for your Passion Camp registration.</i></p>

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
                                        <p class="mb-0">Passion Camp Ticket x{{ form.num_tickets }}</p>
                                        <span class="text-muted">{{ ticket_price | currency }}</span>
                                    </div>
                                    <div class="col text-right">
                                        <strong>{{ ticket_total | currency }}</strong>
                                    </div>
                                </li>
                                <li class="row order-summary__item" v-show="donation_total > 0">
                                    <div class="col">
                                        <p class="mb-0">Donation</p>
                                    </div>
                                    <div class="col text-right">
                                        <strong>{{ donation_total | currency }}</strong>
                                    </div>
                                </li>
                                <li style="border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; padding: 1rem 0">
                                    <div class="flex form-inline">
                                        <input type="text" v-model="form.code" id="" class="form-control" placeholder="Discount Code" style="flex: 1">
                                        <button class="btn btn-secondary ml-3" @click.prevent="applyDiscountCode">Apply</button>
                                    </div>
                                </li>
                                <li class="row order-summary__item order-summary__total">
                                    <div class="col">
                                        <p class="mb-0"><strong>Total</strong></p>
                                    </div>
                                    <div class="col text-right">
                                        <strong>{{ full_amount | currency }}</strong>
                                    </div>
                                </li>
                                <li class="row order-summary__item px-4" v-if="canPayDeposit">
                                    <div class="col">
                                        Deposit Amount
                                    </div>
                                    <div class="col text-right">
                                        {{ deposit_amount | currency }}
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
    import payment from '../Mixins/Payment.js';
    import ticket from './RegisterFormTicket.vue'
    import { debounce, pick } from 'lodash'
    import { Errors } from 'form-backend-validation'

    export default {
        mixins: [payment],
        props: [
            'stripeElements',
            'canPayDeposit',
            'propTicketPrice',
            'grades',
        ],
        components: {
            ticket
        },
        data() {
            return {
                form: {
                    first_name: null,
                    last_name: null,
                    email: null,
                    phone: null,
                    street: null,
                    city: null,
                    state: null,
                    zip: null,
                    num_tickets: null,
                    rep: null,
                    code: null,
                    payment_type: 'full',
                    stripeToken: null,
                    tickets: []
                },
                ticket_price: this.propTicketPrice,
                errors: new Errors(),
                num_tickets: 1
            }
        },
        watch: {
            num_tickets: {
                handler: function (num_tickets) {
                    this.applyDiscountCode()
                    this.form.num_tickets = num_tickets

                    while (this.form.tickets.length < num_tickets) {
                        this.form.tickets.push({})
                    }

                    while (this.form.tickets.length > num_tickets) {
                        this.form.tickets.pop();
                    }
                },
                immediate: true
            }
        },
        computed: {
            ticket_total() {
                return this.form.num_tickets * this.ticket_price;
            },
            donation_total() {
                return 0;
            },
            deposit_amount() {
                return this.form.num_tickets * 75 + this.donation_total;
            },
            full_amount() {
                return this.ticket_total + this.donation_total;
            },
            payment_amount() {
                return this.canPayDeposit && this.form.payment_type == 'deposit' ? this.deposit_amount : this.full_amount;
            },
        },
        methods: {
            submitHandler(e) {
                return this.elementsSubmitHandler(e);
            },
            stripeTokenHandler(token) {
                this.form.stripeToken = token
                this.submit()
            },
            submit() {
                axios.post(this.$attrs.action, this.form).then(response => {
                    window.location = response.data.location
                }).catch(error => {
                    this.errors = new Errors(error.response.data.errors)
                    this.Payment.occupied = false
                    this.$nextTick(() => {
                        document.getElementById('form-submission-alert').scrollIntoView()
                    })
                })
            },
            applyDiscountCode: debounce(function () {
                axios
                    .post('/ticket-price', this.form)
                    .then(({data}) => {
                        this.ticket_price = data / 100
                    })

                return;
            }, 300, { leading: true })
        },
        filters: {
            forTicket(errors, index) {
                let key = `tickets.${index}.`

                let filtered = Object.keys(errors.all())
                    .filter(e => e.startsWith(key))
                    .reduce((filtered, k) => {
                        filtered[k.replace(key, '')] = errors.get(k)
                        return filtered
                    }, {})

                return new Errors(filtered)
            }
        }
    }
</script>
