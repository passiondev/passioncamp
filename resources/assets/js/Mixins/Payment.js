
import style from '../lib/stripe-elements-style'

export default {
    mounted() {
        if (this.stripeElements) {
            let elements = stripe.elements();
            let card = this.Payment.card = elements.create('card', {style});
            card.mount(`#${this.stripeElements}`);

            card.addEventListener('change', ({error}) => {
                if (error) {
                    this.Payment.errors.push(error.message);
                } else {
                    this.Payment.errors = [];
                }
            });

            // this.Payment.form = this.$refs.form

            // var paymentRequest = stripe.paymentRequest({
            //     country: 'US',
            //     currency: 'usd',
            //     total: {
            //         label: 'Passion Camp',
            //         amount: 100 * this.Payment.amount,
            //     },
            // });

            // var prButton = elements.create('paymentRequestButton', {
            //     paymentRequest: paymentRequest,
            // });

            // // Check the availability of the Payment Request API first.
            // paymentRequest.canMakePayment().then(function (result) {
            //     if (result) {
            //         prButton.mount('#payment-request-button');
            //     } else {
            //         document.getElementById('payment-request-button').style.display = 'none';
            //     }
            // });

            // paymentRequest.on('token', (ev) => {
            //     this.stripeTokenHandler(ev.token.id);
            //     ev.complete('success');
            // });
        }
    },
    data() {
        return {
            Payment: {
                card_number: '',
                card_exp: '',
                card_cvc: '',
                form: null,
                errors: [],
                occupied: false,
                card: null,
                amount: -1
            }
        }
    },
    computed: {
        stripeCardObject() {
            return {
                number: this.Payment.card_number,
                exp: this.Payment.card_exp,
                cvc: this.Payment.card_cvc
            }
        }
    },
    methods: {
        elementsSubmitHandler(e) {
            e.preventDefault();

            if (this.Payment.occupied) {
                return;
            }

            this.Payment.form = e.target;
            this.Payment.occupied = true;

            stripe.createToken(this.Payment.card).then((result) => {
                if (result.error) {
                    this.stripeErrorHandler(result.error.message);
                } else {
                    this.stripeTokenHandler(result.token.id);
                }
            });
        },
        stripeTokenHandler(token) {
            const input = document.createElement('input')
                  input.type = 'hidden';
                  input.name = 'stripeToken';
                  input.value = token;

            this.Payment.form.appendChild(input);
            this.Payment.form.submit();
        },
        stripeSubmitHandler(e) {
            this.Payment.errors = [];
            let cardType = Stripe.card.cardType(this.Payment.card_number);

            if (! Stripe.card.validateCardNumber(this.Payment.card_number)) {
                this.Payment.errors.push('Your card number is not valid.');
            }

            if (! Stripe.card.validateExpiry(this.Payment.card_exp)) {
                this.Payment.errors.push('Your card expiration date is not valid.');
            }

            if (! Stripe.card.validateCVC(this.Payment.card_cvc)) {
                this.Payment.errors.push('Your card security code date is not valid.');
            }

            if (this.Payment.errors.length) {
                return false;
            }

            this.Payment.form = e.target;
            this.Payment.occupied = true;

            Stripe.card.createToken(this.stripeCardObject, this.stripeResponseHandler);
        },
        stripeResponseHandler(status, response) {
            if (response.error) {
                this.stripeErrorHandler(response.error);
            } else {
                this.stripeTokenHandler(response.id);
            }
        },
        stripeErrorHandler(error) {
            this.Payment.errors = [];
            this.Payment.occupied = false;
            this.Payment.errors.push(error);
        }
    }
}
