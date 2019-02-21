<template>

</template>

<script>
    import payment from '../Mixins/Payment.js';
    import ticket from './RegisterFormTicket.vue'
    import { debounce } from 'lodash'

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
        watch: {
            num_tickets(newNumTickets) {
                this.applyDiscountCode()
            }
        },
        computed: {
            ticket_price() {
                return this.localTicketPrice;
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
            applyDiscountCode: debounce(function () {
                axios
                    .post('/ticket-price', {
                        code: this.discountCode,
                        num_tickets: this.num_tickets
                    })
                    .then(({data}) => {
                        this.localTicketPrice = data / 100
                    })

                return;
            }, 300, { leading: true })
        }
    }
</script>
