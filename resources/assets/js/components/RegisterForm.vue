<template>

</template>

<script>
    import payment from '../Mixins/Payment.js';
    import ticket from './RegisterFormTicket.vue'

    export default {
        mixins: [payment],
        props: ['stripeElements', 'canPayDeposit', 'initialCode'],
        components: {
            ticket
        },
        data() {
            let local = {
                discountCode: this.$props.initialCode,
                localTicketPrice: ticket_price
            };

            return Object.assign(vuex, local);
        },
        created() {
            if (this.discountCode) {
                this.applyDiscountCode()
            }
        },
        computed: {
            ticket_price() {
                if (this.localTicketPrice <= 375) {
                    return this.localTicketPrice;
                }

                return this.num_tickets >= 2 ? (this.localTicketPrice - 20) : this.localTicketPrice;
            },
            ticket_total() {
                return this.num_tickets * this.ticket_price;
            },
            donation_total() {
                return parseInt(this.fund_amount == 'other' ? this.fund_amount_other : this.fund_amount) || 0;
            },
            deposit_amount() {
                return this.num_tickets * 75 + this.donation_total;
            },
            full_amount() {
                return this.ticket_total + this.donation_total;
            },
            payment_amount() {
                return this.canPayDeposit && this.payment_type == 'deposit' ? this.deposit_amount : this.full_amount;
            },
            tickets() {
                let tickets = this.ticketData;

                while (tickets.length < this.num_tickets) {
                    let number = tickets.length + 1;

                    tickets.push({})
                }

                while (tickets.length > this.num_tickets) {
                    tickets.pop();
                }

                return tickets;
            }
        },
        methods: {
            submitHandler(e) {
                return this.elementsSubmitHandler(e);
            },
            applyDiscountCode() {
                console.log('test')
                console.log(this.discountCode)
                if (this.discountCode == 'rising') {
                    this.localTicketPrice = 365;
                }
            }
        }
    }
</script>
