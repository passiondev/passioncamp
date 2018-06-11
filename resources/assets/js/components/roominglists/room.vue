<template>
    <div class="room card">
        <header class="card-header">
            <div class="d-flex justify-content-between align-items-baseline">
                <slot name="header">
                    <h4>
                        <slot name="organization"></slot>
                        {{ room.name }}
                    </h4>
                </slot>
                <slot name="actions"></slot>
            </div>
            <h6 class="mb-0 text-muted" style="font-size:90%">{{ room.description }}</h6>
            <p class="card-text text-muted" style="font-size:90%">{{ room.notes }}</p>
        </header>
        <div class="card-block">
            <div class="statistics justify-content-center mb-3">
                <div class="blue statistic">
                    <div class="value">{{ tickets.length }}</div>
                    <div class="label">Assigned</div>
                </div>
                <div class="green statistic">
                    <div class="value">{{ room.capacity }}</div>
                    <div class="label">Capacity</div>
                </div>
            </div>

            <div class="tickets">
                <div class="list-group">
                    <ticket v-for="ticket in tickets" :key="ticket.id" :ticket="ticket"></ticket>
                </div>
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
        props: ['room', 'tickets'],
        data() {
            return {
                draggableOptions: {
                    group: 'tickets',
                    scroll: false,
                    sort: false
                },
            }
        },

        methods: {
            onChange(event) {
                return false

                if (event.added) {
                    let ticket = event.added.element
                    let oldRoomId = ticket.room_id

                    if (ticket.organization_id != this.room.organization_id) {
                        return
                    }

                    if (this.tickets.length > this.room.capacity) {
                        return
                    }

                    ticket.room_id = this.room.id

                    axios.post(`/rooms/${this.room.id}/assignments`, {
                        ticket: ticket.id
                    })
                    .catch(() => ticket.room_id = oldRoomId)
                }
            }
        }
    }
</script>
