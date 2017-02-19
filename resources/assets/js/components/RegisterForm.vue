<template>

</template>

<script>
    import payment from '../Mixins/Payment.js';

    export default {
        mixins: [payment],
        components: {
            'ticket': require('./RegisterFormTicket.vue')
        },
        data() {
            let local = {};

            return Object.assign(vuex, local);
        },
        computed: {
            ticket_price() {
                return this.num_tickets >= 2 ? (ticket_price - 20) : ticket_price;
            },
            ticket_total() {
                return this.num_tickets * this.ticket_price;
            },
            donation_total() {
                return parseInt(this.fund_amount == 'other' ? this.fund_amount_other : this.fund_amount) || 0;
            },
            deposit_amount() {
                return this.num_tickets * 60 + this.donation_total;
            },
            full_amount() {
                return this.ticket_total + this.donation_total;
            },
            payment_amount() {
                return this.payment_amount_type == 'deposit' ? this.deposit_amount : this.full_amount;
            },
            tickets() {
                let tickets = this.ticketData;

                while (tickets.length < this.num_tickets) {
                    let number = tickets.length + 1;

                    tickets.push({
                        number: number,
                    })
                }

                while (tickets.length > this.num_tickets) {
                    tickets.pop();
                }

                return tickets;
            }
        },
        methods: {
            submitHandler(e) {
                return this.stripeSubmitHandler(e);
            }
        }
    }
</script>
