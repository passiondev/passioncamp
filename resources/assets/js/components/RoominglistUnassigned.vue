<template>
    <div id="unassigned" class="list-group">
        <roominglist-ticket v-for="ticket in tickets" :ticket="ticket"></roominglist-ticket>
    </div>
</template>
<script>
    import RoominglistTicket from './RoominglistTicket'

    export default {
        data() {
            return {
                // 'tickets': window.store.unassigned,
                tickets: store.unassigned
            }
        },
        components: {
            RoominglistTicket,
        }
    }
</script>
