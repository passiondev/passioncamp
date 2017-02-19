
export default {
    created() {
        console.log('mixin hook called');
    },
    data() {
        return {
            Payment: {
                card_number: '',
                card_exp: '',
                card_cvc: '',
                form: null,
                errors: [],
                occupied: false
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
                this.Payment.occupied = false;
                this.Payment.errors.push(response.error);
            } else {
                let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'stripeToken';
                    input.value = response.id;
                this.Payment.form.appendChild(input);

                this.Payment.form.submit();
            }
        }
    }
}
