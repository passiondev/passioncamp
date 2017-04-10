<template>
    <div class="tickets overflowing col-3 d-flex flex-column h-100">
        <h1>Tickets</h1>

        <div id="unassigned-tickets-scroll" style="overflow-y: scroll;padding-bottom: 20px">
            <div id="unassigned" class="list-group">
                <draggable v-model="tickets" :move="move" @add="add" @start="start" @end="end" :options="draggableOptions">
                    <roominglist-ticket v-for="(ticket, index) in tickets" :key="ticket.id" :ticket="ticket"></roominglist-ticket>
                </draggable>
            </div>
        </div>
    </div>
</template>
<script>
    import RoominglistTicket from './RoominglistTicket'
    import draggable from 'vuedraggable'
    import Fuse from 'fuse.js'

    export default {
        data() {
            return {
                search: '',
                draggableOptions: {
                    group: 'tickets',
                    scroll: false
                },
                tickets: _.values(store.unassigned),
            }
        },

        computed: {

        //     tickets() {
        //         if (this.search.length < 3) {
        //             return store.unassigned;
        //         }

        //         var options = {
        //             shouldSort: true,
        //             threshold: 0.3,
        //             distance: 100,
        //             maxPatternLength: 32,
        //             minMatchCharLength: 3,
        //             keys: ['name'],
        //             id: 'id'
        //         };

        //         let fuse = new Fuse(store.unassigned, options);
        //         let results = fuse.search(this.search);

        //         return results.map(n => store.unassigned.find(ticket => ticket.id == n));
        //     },
        },

        created() {

        },

        methods: {
            searched: function (tickets) {
                if (this.search.length < 3) {
                    return tickets;
                }

                var options = {
                    shouldSort: true,
                    threshold: 0.3,
                    distance: 100,
                    maxPatternLength: 32,
                    minMatchCharLength: 3,
                    keys: ['name'],
                    id: 'id'
                };

                let fuse = new Fuse(tickets, options);
                let results = fuse.search(this.search);

                return results.map(n => tickets.find(ticket => ticket.id == n));
            },

            move(evt, originalEvent) {
                return ! evt.to.dataset.full;
            },
            add(evt) {
                axios.delete(evt.from.dataset.url, {
                    data: {
                        ticket: evt.item.dataset.ticket
                    }
                })
            },
            start(evt) {
                store.dragging = true;
            },
            end(evt) {
                store.dragging = true;
            },
        },

        components: {
            RoominglistTicket,
            draggable
        },
    }
</script>
