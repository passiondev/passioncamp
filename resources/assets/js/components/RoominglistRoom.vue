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
                    <div class="value">{{ assigned }}</div>
                    <div class="label">Assigned</div>
                </div>
                <div class="green statistic">
                    <div class="value">{{ room.capacity }}</div>
                    <div class="label">Capacity</div>
                </div>
            </div>

            <div class="tickets">
                <div class="list-group">
                    <draggable v-model="tickets" :options="draggableOptions" :move="move" @add="add" @start="start" @end="end" style="min-height: 1rem" :data-url="url" :data-full="full">
                        <roominglist-ticket v-for="ticket in tickets" :key="ticket.id" :ticket="ticket"></roominglist-ticket>
                    </draggable>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import RoominglistTicket from './RoominglistTicket'
    import draggable from 'vuedraggable'

    export default {
        props: ['room', 'url'],
        data() {
            return {
                tickets: _.values(this.room.ticket_map),
                draggableOptions: {
                    group: 'tickets',
                    scroll: false,
                }
            }
        },

        computed: {
            assigned() {
                return this.tickets.length;
            },
            full() {
                return this.assigned >= this.room.capacity;
            }
        },

        methods: {
            move(evt, originalEvent) {
                return ! evt.to.dataset.full;
            },
            add(evt) {
                axios.post(this.url, {
                    ticket: evt.item.dataset.ticket
                })
            },
            start() {
                store.dragging = true;
            },
            end() {
                store.dragging = false;
            }
        },

        components: {
            RoominglistTicket,
            draggable
        },
    }
</script>
