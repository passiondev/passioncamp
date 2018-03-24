<template>
    <div class="tickets overflowing col-3 d-flex flex-column h-100">
        <h1>Tickets</h1>

        <div id="unassigned-tickets-scroll" style="overflow-y: scroll;padding-bottom: 20px">
            <div id="unassigned" class="list-group">
                <draggable
                    :list="tickets"
                    @change="onChange"
                    :options="draggableOptions">
                    <ticket v-for="ticket in tickets" :key="ticket.id" :ticket="ticket"></ticket>
                </draggable>
            </div>
        </div>
    </div>
</template>
<script>
    import ticket from './ticket'
    import draggable from 'vuedraggable'

    export default {
        components: {
            ticket,
            draggable
        },

        props: ['tickets'],

        data() {
            return {
                draggableOptions: {
                    group: 'tickets',
                    scroll: false,
                    sort: false,
                },
            }
        },

        methods: {
            onChange(event) {
                if (event.added) {
                    let ticket = event.added.element
                    let oldRoomId = ticket.room_id

                    ticket.room_id = null

                    axios.delete(`/tickets/${ticket.id}/assignments`)
                    .catch(() => ticket.room_id = oldRoomId)
                }
            }
        },
    }
</script>
